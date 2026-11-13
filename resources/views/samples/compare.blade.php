<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Local enhancement — before / after</title>
    <style>
        body { font-family: -apple-system, system-ui, sans-serif; background:#0f1115; color:#e6e8eb; margin:0; padding:40px; }
        h1 { font-size: 28px; margin:0 0 6px; }
        p.sub { color:#8c9199; margin:0 0 32px; }
        .pair { display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom: 40px; }
        .pair h3 { grid-column: 1 / -1; margin:0 0 4px; font-size: 18px; }
        .pair figure { margin:0; background:#181b22; border-radius:12px; overflow:hidden; }
        .pair figcaption { padding: 10px 14px; color:#8c9199; font-size:13px; display:flex; justify-content:space-between; }
        .pair img { display:block; width:100%; height:auto; }
        .tag { font-size: 11px; background:#1f242d; padding: 2px 8px; border-radius: 4px; }
        .notes { background:#181b22; border-radius:12px; padding:20px 24px; margin-top:40px; }
        .notes li { margin: 4px 0; color:#c6cad1; }
    </style>
</head>
<body>
    <h1>Local AI enhancement — before / after</h1>
    <p class="sub">Model: <code>isnet-general-use</code> · 100% on-device · no API calls · ~1.3s inference after warm.</p>

    @foreach ($pairs as $p)
        <section class="pair">
            <h3>{{ $p['title'] }}</h3>
            <figure>
                <img src="{{ asset('storage/samples/'.$p['before']) }}" alt="before">
                <figcaption><span>Original — phone shot</span><span class="tag">before</span></figcaption>
            </figure>
            <figure>
                <img src="{{ asset('storage/enhanced/'.$p['after']) }}" alt="after">
                <figcaption><span>Studio composite — isnet cutout + turntable</span><span class="tag">after</span></figcaption>
            </figure>
            @if (! empty($p['note']))
                <div style="grid-column: 1 / -1; color:#8c9199; font-size:13px; margin-top:-4px;">{{ $p['note'] }}</div>
            @endif
        </section>
    @endforeach

    <div class="notes">
        <strong>Pipeline:</strong>
        <ul>
            <li>isnet-general-use ONNX → foreground alpha mask (~1.3s/image via onnxruntime + CoreML)</li>
            <li>Morphological opening → largest connected component → closing + hole fill (kills background fragments)</li>
            <li>Aspect-aware scaling onto a seamless cyclorama backdrop with a circular turntable disc</li>
            <li>Footprint-shaped contact shadow + subtle vertical-flip floor reflection</li>
        </ul>
        <strong style="margin-top:16px; display:block;">Cost:</strong>
        <ul>
            <li>API cost per image: <strong>$0</strong></li>
            <li>Compute cost per image: pure CPU/GPU time (~1.3s on this Mac via CoreML, &lt;500ms on a modest GPU)</li>
            <li>Model files: <code>storage/app/models/{u2net,isnet-general-use}.onnx</code> (~170 MB each, downloaded once)</li>
        </ul>
        <strong style="margin-top:16px; display:block;">Remaining quality ceiling:</strong>
        <ul>
            <li>Reflection removal from glossy paint (your differentiator) — needs an inpainting model (SDXL + ControlNet or custom), still $0 API, but a real ML project</li>
            <li>Multi-car scenes where cars physically touch — needs instance segmentation (YOLOv8-seg filtered to <code>car</code> class as a pre-crop before the mask)</li>
        </ul>
    </div>
</body>
</html>
