<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Billing</h2></x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="rounded-md bg-red-50 border border-red-200 text-red-900 px-4 py-2 text-sm">{{ $errors->first() }}</div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold">Current plan</h3>
                @if ($subscription)
                    <div class="mt-2 text-sm">
                        <div class="font-medium">{{ $subscription->name }} — {{ $subscription->stripe_status }}</div>
                        <form method="GET" action="{{ route('billing.portal') }}" class="mt-3">
                            <button class="inline-flex items-center rounded-md border border-slate-300 px-4 py-2 text-sm">Manage in Stripe portal</button>
                        </form>
                    </div>
                @else
                    <p class="mt-2 text-sm text-slate-500">You're on a free trial. Pick a plan below to continue.</p>
                @endif
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                @foreach ($plans as $plan)
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="font-bold text-lg">{{ $plan->name }}</div>
                        <div class="mt-2 text-3xl font-bold">{{ $plan->priceFormatted() }}<span class="text-sm text-slate-500 font-normal"> / mo</span></div>
                        <p class="mt-1 text-sm text-slate-600">{{ $plan->tagline }}</p>
                        <ul class="mt-4 space-y-1 text-sm">
                            @foreach (($plan->features ?? []) as $f)
                                <li>✓ {{ $f }}</li>
                            @endforeach
                        </ul>
                        <form method="POST" action="{{ route('billing.subscribe', $plan) }}" class="mt-6">
                            @csrf
                            <button class="w-full rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">Subscribe</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
