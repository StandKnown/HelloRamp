<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPlanController extends Controller
{
    public function index(): View
    {
        return view('admin.plans.index', [
            'plans' => Plan::orderBy('sort_order')->get(),
        ]);
    }

    public function edit(Plan $plan): View
    {
        return view('admin.plans.edit', ['plan' => $plan]);
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'price_cents' => ['required', 'integer', 'min:0'],
            'stripe_price_id' => ['nullable', 'string', 'max:160'],
            'vehicle_limit' => ['required', 'integer', 'min:0'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('status', 'Plan saved.');
    }
}
