<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(Request $request): View
    {
        return view('billing.index', [
            'plans' => Plan::active()->orderBy('sort_order')->get(),
            'subscription' => $request->user()->subscription('default'),
        ]);
    }

    public function subscribe(Request $request, Plan $plan): RedirectResponse
    {
        if (! $plan->stripe_price_id) {
            return back()->withErrors(['plan' => 'This plan is not yet configured for billing. Set STRIPE keys and stripe_price_id in config.']);
        }

        $checkout = $request->user()
            ->newSubscription('default', $plan->stripe_price_id)
            ->checkout([
                'success_url' => route('billing.index').'?checkout=success',
                'cancel_url' => route('billing.index').'?checkout=cancel',
            ]);

        return redirect($checkout->url);
    }

    public function portal(Request $request): RedirectResponse
    {
        return $request->user()->redirectToBillingPortal(route('billing.index'));
    }
}
