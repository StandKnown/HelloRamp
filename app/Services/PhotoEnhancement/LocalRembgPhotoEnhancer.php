<?php

namespace App\Services\PhotoEnhancement;

use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Local enhancement using u2net ONNX via scripts/enhance.py.
 * Zero API cost — the whole pipeline runs on-device via onnxruntime.
 */
class LocalRembgPhotoEnhancer implements PhotoEnhancer
{
    public function __construct(
        private string $python,
        private string $script,
        private string $backdrop,
        private string $model,
        private string $disk = 'public',
        private int $timeout = 120,
    ) {
    }

    public function enhance(Photo $photo): EnhancementResult
    {
        $diskRoot = Storage::disk($this->disk)->path('');
        $inputAbs = $diskRoot.ltrim($photo->original_path, '/');

        if (! is_file($inputAbs)) {
            throw new RuntimeException("Original photo missing on disk: {$inputAbs}");
        }

        $enhancedRelative = 'enhanced/'.Str::uuid().'.jpg';
        $outputAbs = $diskRoot.$enhancedRelative;

        $process = new Process([
            $this->python,
            $this->script,
            '--input', $inputAbs,
            '--output', $outputAbs,
            '--backdrop', $this->backdrop,
            '--model', $this->model,
        ]);
        $process->setTimeout($this->timeout);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException('enhance.py failed: '.$process->getErrorOutput());
        }

        $meta = json_decode(trim($process->getOutput()), true) ?: [];

        return new EnhancementResult(
            enhancedPath: $enhancedRelative,
            thumbnailPath: null,
            metadata: [
                'provider' => $this->name(),
                'background_removed' => true,
                'reflection_cleanup' => false,
                'ms_total' => $meta['ms_total'] ?? null,
                'ms_model_load' => $meta['ms_model_load'] ?? null,
                'ms_inference' => $meta['ms_rembg'] ?? null,
                'enhanced_at' => now()->toIso8601String(),
            ],
        );
    }

    public function name(): string
    {
        return 'local_rembg_u2net';
    }
}
