#!/usr/bin/env python3
"""
Local car-photo enhancement pipeline. No external APIs.

Pipeline:
  1. Load input photo
  2. u2net alpha mask (via onnxruntime)
  3. Trim cutout
  4. Detect the car's tire-footprint line (bottom of the mask per column)
  5. Build a footprint-shaped ground shadow projected onto the floor plane
  6. Build a subtle floor reflection (vertical-flip of the car's bottom half,
     faded via alpha gradient)
  7. Composite shadow → reflection → car onto the studio cyclorama + turntable,
     anchoring the car's tire line to the turntable's horizontal centreline
  8. Save JPEG
"""

from __future__ import annotations

import argparse
import json
import sys
import time
from pathlib import Path

import numpy as np
import onnxruntime as ort
from PIL import Image, ImageDraw, ImageFilter
from scipy import ndimage

# Per-model preprocessing. Add new entries here to support additional ONNX
# segmenters without touching the rest of the pipeline.
MODEL_PRESETS = {
    "u2net": {
        "input_size": 320,
        "mean": (0.485, 0.456, 0.406),
        "std": (0.229, 0.224, 0.225),
    },
    "isnet": {
        # isnet-general-use expects 1024x1024, simple [-0.5, 0.5] normalization.
        "input_size": 1024,
        "mean": (0.5, 0.5, 0.5),
        "std": (1.0, 1.0, 1.0),
    },
}

# Where the turntable disc sits on the backdrop. Must match make_backdrop.py.
TURNTABLE_CY_RATIO = 0.76
TURNTABLE_RX_RATIO = 0.46
TURNTABLE_RY_RATIO = 0.13


def preset_for(model_path: Path) -> dict:
    name = model_path.stem.lower()
    if "isnet" in name:
        return MODEL_PRESETS["isnet"]
    return MODEL_PRESETS["u2net"]


def load_session(model_path: Path) -> ort.InferenceSession:
    providers = ort.get_available_providers()
    preferred = [p for p in ["CoreMLExecutionProvider", "CUDAExecutionProvider", "CPUExecutionProvider"] if p in providers]
    return ort.InferenceSession(str(model_path), providers=preferred)


def predict_alpha(session: ort.InferenceSession, img: Image.Image, preset: dict) -> Image.Image:
    """
    Produce an alpha mask containing ONLY the main car.

    Dispatches preprocessing based on the model preset (u2net vs isnet).
    Then always applies the same post-processing:
      - Binary threshold
      - Morphological opening (two-pass) + largest connected component
      - Morphological closing + hole fill
      - Light Gaussian blur for a soft matte
    """
    size = preset["input_size"]
    resized = img.convert("RGB").resize((size, size), Image.LANCZOS)
    arr = np.asarray(resized, dtype=np.float32) / 255.0
    mean = np.array(preset["mean"], dtype=np.float32)
    std = np.array(preset["std"], dtype=np.float32)
    arr = (arr - mean) / std
    arr = np.transpose(arr, (2, 0, 1))[None, ...]

    input_name = session.get_inputs()[0].name
    outputs = session.run(None, {input_name: arr})
    raw = outputs[0][0][0]  # (320, 320) float

    mx, mn = raw.max(), raw.min()
    if mx - mn > 1e-6:
        raw = (raw - mn) / (mx - mn)

    cleaned = largest_component_mask(raw, threshold=0.45)

    mask8 = (cleaned * 255.0).clip(0, 255).astype(np.uint8)
    alpha_small = Image.fromarray(mask8, mode="L")
    # Upscale to original image size, then feather the edge slightly.
    alpha = alpha_small.resize(img.size, Image.LANCZOS).filter(ImageFilter.GaussianBlur(radius=1.4))
    return alpha


def largest_component_mask(raw: np.ndarray, threshold: float = 0.45) -> np.ndarray:
    """
    Isolate the single largest foreground blob, trimming any other objects u2net
    grabbed (cars behind, houses, trees). Returns a smooth 0..1 float mask.

    Two-pass approach:
      Pass 1 — small opening (5x5) + largest component. Handles the common case
               where a thin fringe connects the car to background.
      Pass 2 — aggressive opening (11x11) + largest component. Breaks thicker
               bridges to a pickup parked behind the subject car. If the second
               pass annihilates too much (rare), we fall back to pass 1.
    """
    binary = raw > threshold

    def opening_and_largest(mask: np.ndarray, kernel_size: int) -> np.ndarray:
        kernel = np.ones((kernel_size, kernel_size), dtype=bool)
        opened = ndimage.binary_opening(mask, structure=kernel, iterations=1)
        labels, n = ndimage.label(opened)
        if n == 0:
            return mask
        sizes = ndimage.sum(opened, labels, index=range(1, n + 1))
        largest_label = 1 + int(np.argmax(sizes))
        return labels == largest_label

    pass1 = opening_and_largest(binary, kernel_size=5)
    pass1_count = int(pass1.sum())

    pass2 = opening_and_largest(pass1, kernel_size=11)
    pass2_count = int(pass2.sum())

    # If aggressive opening killed more than 35% of the car, keep pass1.
    if pass2_count < pass1_count * 0.65 or pass2_count < 100:
        kept = pass1
    else:
        kept = pass2

    # Fill holes + close small gaps (chrome, grille, inside-of-wheel).
    closed = ndimage.binary_closing(kept, structure=np.ones((9, 9), dtype=bool), iterations=2)
    closed = ndimage.binary_fill_holes(closed)

    return ndimage.gaussian_filter(closed.astype(np.float32), sigma=0.8)


def cutout_with_alpha(img: Image.Image, alpha: Image.Image) -> Image.Image:
    rgba = img.convert("RGBA")
    rgba.putalpha(alpha)
    return rgba


def trim_to_content(img: Image.Image, alpha_threshold: int = 12) -> Image.Image:
    alpha = img.getchannel("A").point(lambda v: 255 if v > alpha_threshold else 0)
    bbox = alpha.getbbox()
    return img if bbox is None else img.crop(bbox)


def footprint_line(img: Image.Image, alpha_threshold: int = 32) -> np.ndarray:
    """
    For each column, return the y of the lowest opaque pixel. Columns with no
    opaque pixels are set to -1. Used to build footprint shadow + find tire baseline.
    """
    alpha = np.asarray(img.getchannel("A"))
    opaque = alpha > alpha_threshold
    # argmax from the bottom: flip vertically, find first True, convert back
    flipped = opaque[::-1]
    any_opaque = flipped.any(axis=0)
    first_from_bottom = np.argmax(flipped, axis=0)
    bottom_y = np.where(any_opaque, (opaque.shape[0] - 1) - first_from_bottom, -1)
    return bottom_y


def build_footprint_shadow(car: Image.Image, strength: float = 0.70) -> Image.Image:
    """
    Shadow projected onto the floor plane from the bottom silhouette of the car.
    Compressed vertically ~0.25x to simulate perspective, then heavily blurred.
    """
    w, h = car.size
    footprint = footprint_line(car)

    shadow = Image.new("L", (w, h), 0)
    sd = ImageDraw.Draw(shadow)
    for x in range(w):
        y = footprint[x]
        if y < 0:
            continue
        # Darker directly under the car, fading out quickly
        sd.line([(x, y), (x, min(h - 1, y + 20))], fill=int(255 * strength))

    # Compress vertically to simulate flat ground, then massive blur.
    sh_h = max(40, int(h * 0.22))
    shadow_floor = shadow.resize((w, sh_h), Image.LANCZOS)
    pad = int(w * 0.10)
    canvas = Image.new("L", (w + pad * 2, sh_h + 20), 0)
    canvas.paste(shadow_floor, (pad, 10))
    canvas = canvas.filter(ImageFilter.GaussianBlur(radius=max(14, w // 40)))

    rgba = Image.new("RGBA", canvas.size, (0, 0, 0, 0))
    rgba.putalpha(canvas)
    return rgba


def build_reflection(car: Image.Image, fade_strength: float = 0.28) -> Image.Image:
    """
    Subtle floor reflection — vertical flip, cropped to the bottom 45% of the car,
    then faded via alpha gradient so only a hint reaches the floor.
    """
    w, h = car.size
    bottom_slice = car.crop((0, int(h * 0.55), w, h))
    refl = bottom_slice.transpose(Image.FLIP_TOP_BOTTOM)

    # Build alpha fade: full-ish at the tire line, zero further down.
    rw, rh = refl.size
    gradient = Image.new("L", (rw, rh), 0)
    for y in range(rh):
        t = y / max(1, rh - 1)
        g = int(fade_strength * 255 * (1 - t) ** 1.8)
        for x in range(rw):
            gradient.putpixel((x, y), g)
    # Multiply existing alpha by the gradient.
    orig_alpha = np.asarray(refl.getchannel("A"), dtype=np.float32) / 255.0
    grad_arr = np.asarray(gradient, dtype=np.float32) / 255.0
    new_alpha = (orig_alpha * grad_arr * 255).clip(0, 255).astype(np.uint8)
    refl.putalpha(Image.fromarray(new_alpha, mode="L"))
    # Very slight blur to soften.
    refl = refl.filter(ImageFilter.GaussianBlur(radius=1.2))
    return refl


def enhance(
    input_path: Path,
    output_path: Path,
    backdrop_path: Path,
    model_path: Path,
    max_output_width: int = 1800,
) -> dict:
    t0 = time.time()

    src = Image.open(input_path).convert("RGB")
    if src.width > max_output_width:
        ratio = max_output_width / src.width
        src = src.resize((max_output_width, int(src.height * ratio)), Image.LANCZOS)

    session = load_session(model_path)
    preset = preset_for(model_path)
    t_load = time.time() - t0

    alpha = predict_alpha(session, src, preset)
    cutout = cutout_with_alpha(src, alpha)
    t_mask = time.time() - t0

    car = trim_to_content(cutout)

    backdrop = Image.open(backdrop_path).convert("RGBA")
    bw, bh = backdrop.size

    # Scale the car so it sits comfortably on the turntable. Fit by the dimension
    # that would otherwise crop — handles both long sedans (width-bound) and
    # square/portrait phone shots like a straight-on rear 3/4 (height-bound).
    scale_w = (bw * 0.62) / car.width
    scale_h = (bh * 0.66) / car.height
    scale = min(scale_w, scale_h)
    target_width = int(car.width * scale)
    target_height = int(car.height * scale)
    car = car.resize((target_width, target_height), Image.LANCZOS)

    # Where the car's tires sit (bottommost opaque y per column — take the median of
    # the bottom 10% of columns around centre, more robust than max over all).
    fp = footprint_line(car)
    valid = fp[fp >= 0]
    if valid.size:
        tire_y = int(np.percentile(valid, 95))
    else:
        tire_y = car.height - 1

    # Anchor the tires on the turntable's horizontal centre-line.
    turntable_cy = int(bh * TURNTABLE_CY_RATIO)
    cx = (bw - car.width) // 2
    cy = turntable_cy - tire_y

    # Shadow (floor projection of footprint) sits at the tire line and spreads downward.
    shadow = build_footprint_shadow(car)
    sw, sh = shadow.size
    sx = cx + (car.width - sw) // 2
    sy = cy + tire_y - int(sh * 0.45)

    # Reflection (soft mirror of the car's lower half), tucked right under the tires.
    reflection = build_reflection(car)
    rw, rh = reflection.size
    rx = cx
    ry = cy + tire_y + 2  # just below the tire line

    composite = backdrop.copy()
    composite.alpha_composite(reflection, (rx, ry))
    composite.alpha_composite(shadow, (sx, sy))
    composite.alpha_composite(car, (cx, cy))

    output_path.parent.mkdir(parents=True, exist_ok=True)
    composite.convert("RGB").save(output_path, "JPEG", quality=92)

    total = time.time() - t0
    return {
        "ok": True,
        "ms_total": int(total * 1000),
        "ms_model_load": int(t_load * 1000),
        "ms_rembg": int((t_mask - t_load) * 1000),
        "output": str(output_path),
        "subject_width": car.width,
        "subject_height": car.height,
        "tire_y": int(tire_y),
        "output_width": composite.width,
        "output_height": composite.height,
    }


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--input", required=True, type=Path)
    parser.add_argument("--output", required=True, type=Path)
    parser.add_argument("--backdrop", required=True, type=Path)
    parser.add_argument("--model", required=True, type=Path)
    parser.add_argument("--max-width", type=int, default=1800)
    args = parser.parse_args()

    try:
        result = enhance(args.input, args.output, args.backdrop, args.model, args.max_width)
        print(json.dumps(result))
        return 0
    except Exception as exc:  # noqa: BLE001
        print(json.dumps({"ok": False, "error": str(exc)}), file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
