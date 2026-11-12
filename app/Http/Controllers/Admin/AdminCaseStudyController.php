<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminCaseStudyController extends Controller
{
    public function index(): View
    {
        return view('admin.case-studies.index', [
            'studies' => CaseStudy::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.case-studies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['dealership_name']).'-'.Str::random(4);
        $study = CaseStudy::create($data);

        return redirect()->route('admin.case-studies.edit', $study)->with('status', 'Case study created.');
    }

    public function edit(CaseStudy $caseStudy): View
    {
        return view('admin.case-studies.edit', ['study' => $caseStudy]);
    }

    public function update(Request $request, CaseStudy $caseStudy): RedirectResponse
    {
        $caseStudy->update($this->validated($request));

        return redirect()->route('admin.case-studies.edit', $caseStudy)->with('status', 'Case study saved.');
    }

    public function destroy(CaseStudy $caseStudy): RedirectResponse
    {
        $caseStudy->delete();

        return redirect()->route('admin.case-studies.index')->with('status', 'Case study deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'slug' => ['nullable', 'string', 'max:160'],
            'dealership_name' => ['required', 'string', 'max:160'],
            'location' => ['nullable', 'string', 'max:160'],
            'headline' => ['required', 'string', 'max:255'],
            'subheadline' => ['nullable', 'string', 'max:255'],
            'quote' => ['nullable', 'string', 'max:2000'],
            'quote_author' => ['nullable', 'string', 'max:160'],
            'quote_author_role' => ['nullable', 'string', 'max:160'],
            'body' => ['nullable', 'string'],
            'metric_label' => ['nullable', 'string', 'max:120'],
            'metric_value' => ['nullable', 'string', 'max:40'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
        ]);
    }
}
