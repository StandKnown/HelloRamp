#!/usr/bin/env python3
"""
Studio backdrop generator — seamless cyclorama with a circular turntable disc
on the floor. Target look: the Nissan Rogue reference shot.
"""

from __future__ import annotations

import argparse
from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter


def make_backdrop(width: int = 2400, height: int = 1500) -> Image.Image:
    img = Image.new("RGB", (width, height), (230, 232, 236))
    px = img.load()

    # Vertical tone map — no hard horizon.
    # Top of wall: mid-grey. Mid-wall: cool grey. Transition into floor: slight warm lift.
    # Floor: brighter near front, slight falloff toward frame edges.
    horizon_y = int(height * 0.58)
    floor_fade = int(height * 0.08)  # depth of the wall→floor smoothing band

    for y in range(height):
        if y < horizon_y - floor_fade:
            # Pure wall gradient (darker at top → lighter near horizon)
            t = y / max(1, horizon_y - floor_fade)
            r = int(68 + (198 - 68) * t)
            g = int(72 + (203 - 72) * t)
            b = int(79 + (209 - 79) * t)
        elif y < horizon_y + floor_fade:
            # Smooth wall↔floor transition — same tone top/bottom of band
            t = (y - (horizon_y - floor_fade)) / (2 * floor_fade)
            # ease
            s = t * t * (3 - 2 * t)
            r = int(198 + (225 - 198) * s)
            g = int(203 + (227 - 203) * s)
            b = int(209 + (230 - 209) * s)
        else:
            # Floor fade — brightest just past horizon, gentle falloff toward bottom
            t = (y - (horizon_y + floor_fade)) / max(1, height - (horizon_y + floor_fade))
            r = int(225 - 18 * t)
            g = int(227 - 18 * t)
            b = int(230 - 18 * t)

        for x in range(width):
            px[x, y] = (r, g, b)

    # Soft vignette so corners sit back visually.
    vignette = Image.new("L", (width, height), 0)
    vd = ImageDraw.Draw(vignette)
    vd.ellipse(
        (-int(width * 0.3), -int(height * 0.3), int(width * 1.3), int(height * 1.3)),
        fill=255,
    )
    vignette = vignette.filter(ImageFilter.GaussianBlur(radius=180))
    dark = Image.new("RGB", (width, height), (40, 44, 52))
    img = Image.composite(img, dark, vignette)

    # Turntable disc on the floor — elliptical (perspective-flattened circle).
    disc_cx = width // 2
    disc_cy = int(height * 0.76)
    disc_rx = int(width * 0.46)
    disc_ry = int(height * 0.13)

    # Outer ring: slightly darker than floor
    disc = Image.new("L", (width, height), 0)
    dd = ImageDraw.Draw(disc)
    dd.ellipse(
        (disc_cx - disc_rx, disc_cy - disc_ry, disc_cx + disc_rx, disc_cy + disc_ry),
        fill=255,
    )
    disc = disc.filter(ImageFilter.GaussianBlur(radius=4))
    darker_floor = Image.new("RGB", (width, height), (200, 203, 208))
    img = Image.composite(darker_floor, img, disc)

    # Inner soft highlight on the disc
    inner = Image.new("L", (width, height), 0)
    ind = ImageDraw.Draw(inner)
    ind.ellipse(
        (
            disc_cx - int(disc_rx * 0.78),
            disc_cy - int(disc_ry * 0.78),
            disc_cx + int(disc_rx * 0.78),
            disc_cy + int(disc_ry * 0.78),
        ),
        fill=120,
    )
    inner = inner.filter(ImageFilter.GaussianBlur(radius=60))
    white = Image.new("RGB", (width, height), (238, 240, 243))
    img = Image.composite(white, img, inner)

    # Thin rim line on the disc edge for the HelloRamp look.
    rim = Image.new("L", (width, height), 0)
    rd = ImageDraw.Draw(rim)
    rd.ellipse(
        (disc_cx - disc_rx, disc_cy - disc_ry, disc_cx + disc_rx, disc_cy + disc_ry),
        outline=140, width=3,
    )
    rim = rim.filter(ImageFilter.GaussianBlur(radius=1))
    rim_rgb = Image.new("RGB", (width, height), (155, 158, 164))
    img = Image.composite(rim_rgb, img, rim)

    return img


def main() -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("--output", required=True, type=Path)
    parser.add_argument("--width", type=int, default=2400)
    parser.add_argument("--height", type=int, default=1500)
    args = parser.parse_args()

    img = make_backdrop(args.width, args.height)
    args.output.parent.mkdir(parents=True, exist_ok=True)
    img.save(args.output, "PNG", optimize=True)
    print(f"wrote {args.output} ({args.width}x{args.height})")
    return 0


if __name__ == "__main__":
    import sys
    sys.exit(main())
