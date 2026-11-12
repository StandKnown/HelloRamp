<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use App\Models\Plan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('marketing.home', [
            'featuredStudies' => CaseStudy::published()->where('is_featured', true)->take(2)->get(),
            'plans' => Plan::active()->orderBy('sort_order')->get(),
        ]);
    }
}
