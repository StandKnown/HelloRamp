<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(): View
    {
        return view('marketing.pricing', [
            'plans' => Plan::active()->orderBy('sort_order')->get(),
        ]);
    }
}
