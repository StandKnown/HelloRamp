<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SampleCompareController extends Controller
{
    public function index(): View
    {
        return view('samples.compare', [
            'pairs' => [
                ['title' => 'Nissan Rogue — reference target quality', 'before' => 'nissan.jpg',   'after' => 'nissan-enhanced.jpg',   'note' => 'Matches the reference — tires planted on disc, soft shadow, floor reflection.'],
                ['title' => 'Cadillac STS — driveway listing',          'before' => 'cadillac.jpg', 'after' => 'cadillac-enhanced.jpg', 'note' => 'House behind trimmed. "FOR SALE" sign kept (attached to car — correct).'],
                ['title' => 'Hyundai Santa Fe — carport',               'before' => 'hyundai.jpg',  'after' => 'hyundai-enhanced.jpg',  'note' => 'Aspect-aware scaling so the car doesn\'t crop. Alamy watermark in source leaked through.'],
                ['title' => 'Alfa Romeo Giulia — dealer lot',            'before' => 'alfa.jpg',     'after' => 'alfa-enhanced.jpg',     'note' => 'Edge case — pickup physically touches the Alfa\'s roof in source. Edge-only segmenters can\'t split them; needs semantic (car-instance) segmentation.'],
            ],
        ]);
    }
}
