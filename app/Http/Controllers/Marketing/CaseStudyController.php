<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use Illuminate\View\View;

class CaseStudyController extends Controller
{
    public function index(): View
    {
        return view('marketing.case-studies.index', [
            'studies' => CaseStudy::published()->orderByDesc('published_at')->get(),
        ]);
    }

    public function show(CaseStudy $caseStudy): View
    {
        abort_unless($caseStudy->is_published, 404);

        return view('marketing.case-studies.show', ['study' => $caseStudy]);
    }
}
