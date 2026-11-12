@extends('layouts.marketing')
@section('title', 'Pricing · HelloRamp')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <h1 class="text-5xl font-bold tracking-tight text-center">Simple plans. Pick what fits your lot.</h1>
    <p class="mt-4 text-center text-slate-600">All plans include studio-quality photography, reflection cleanup, and human quality review.</p>

    <div class="mt-12 grid md:grid-cols-3 gap-6">
        @foreach ($plans as $plan)
            <div class="rounded-2xl border {{ $plan->is_featured ? 'border-slate-900 shadow-lg' : 'border-slate-200' }} p-8 flex flex-col">
                @if ($plan->is_featured)
                    <span class="self-start uppercase tracking-widest text-xs font-semibold text-emerald-600">Most popular</span>
                @endif
                <div class="mt-2 font-bold text-xl">{{ $plan->name }}</div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-4xl font-bold">{{ $plan->priceFormatted() }}</span>
                    <span class="text-slate-500">/ mo</span>
                </div>
                <p class="mt-2 text-sm text-slate-600">{{ $plan->tagline }}</p>
                <ul class="mt-6 space-y-2 text-sm">
                    @foreach (($plan->features ?? []) as $f)
                        <li class="flex gap-2"><span class="text-emerald-600">✓</span> {{ $f }}</li>
                    @endforeach
                </ul>
                <div class="mt-8">
                    @auth
                        <form method="POST" action="{{ route('billing.subscribe', $plan) }}">
                            @csrf
                            <button class="w-full inline-flex justify-center rounded-md {{ $plan->is_featured ? 'bg-slate-900 text-white' : 'border border-slate-300' }} px-5 py-3 font-medium">Subscribe</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="w-full inline-flex justify-center rounded-md {{ $plan->is_featured ? 'bg-slate-900 text-white' : 'border border-slate-300' }} px-5 py-3 font-medium">Start free trial</a>
                    @endauth
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
