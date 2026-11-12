@extends('layouts.marketing')

@section('title', 'HelloRamp — AI vehicle photography that sells cars faster')

@section('content')
<section class="bg-gradient-to-b from-slate-50 to-white">
    <div class="max-w-7xl mx-auto px-6 py-24 text-center">
        <p class="uppercase tracking-widest text-xs text-slate-500 font-semibold">For dealerships</p>
        <h1 class="text-5xl md:text-6xl font-bold mt-4 tracking-tight">Studio-quality car photos<br>in under 5 minutes.</h1>
        <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto">
            Snap your inventory on a phone. We clean the background, remove reflections, and publish consistent, premium-feeling listings — on every channel.
        </p>
        <div class="mt-8 flex items-center justify-center gap-3">
            <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-slate-900 text-white px-6 py-3 font-medium hover:bg-slate-700">Start free trial</a>
            <a href="{{ route('pricing') }}" class="inline-flex items-center rounded-md border border-slate-300 px-6 py-3 font-medium hover:bg-slate-50">See pricing</a>
        </div>
        <p class="mt-4 text-xs text-slate-500">Trusted by 200+ dealerships · 2M+ vehicles · 1M+ visuals / month</p>
    </div>
</section>

<section class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-3 gap-6">
    @foreach ([
        ['Studio', 'AI vehicle photography — turn phone shots into 360° spins and video tours with consistent lighting and clean backgrounds.', route('products.studio')],
        ['Social', 'Daily posts, reels, and Marketplace listings auto-published to Instagram, TikTok, Facebook, and YouTube.', route('products.social')],
        ['Engage', 'Reply to leads with a personal AI avatar video in about ten seconds.', route('products.engage')],
    ] as [$name, $desc, $href])
        <a href="{{ $href }}" class="block rounded-xl border border-slate-200 p-6 hover:border-slate-900 hover:shadow-sm transition">
            <div class="font-bold text-lg">{{ $name }}</div>
            <p class="mt-2 text-slate-600 text-sm">{{ $desc }}</p>
            <div class="mt-4 text-sm font-medium text-slate-900">Learn more →</div>
        </a>
    @endforeach
</section>

<section class="bg-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold">Why dealers switch</h2>
        <div class="mt-8 grid md:grid-cols-3 gap-8">
            <div>
                <div class="text-5xl font-bold">25%</div>
                <p class="mt-2 text-slate-300">More online leads with consistent, high-trust photography.</p>
            </div>
            <div>
                <div class="text-5xl font-bold">2.5×</div>
                <p class="mt-2 text-slate-300">Faster arrival-to-live — from days to same-day.</p>
            </div>
            <div>
                <div class="text-5xl font-bold">&lt; 5m</div>
                <p class="mt-2 text-slate-300">Per vehicle, from phone capture to published listing.</p>
            </div>
        </div>
    </div>
</section>

@if ($featuredStudies->isNotEmpty())
<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex items-end justify-between">
        <h2 class="text-3xl font-bold">Dealers winning with HelloRamp</h2>
        <a href="{{ route('case-studies.index') }}" class="text-sm font-medium">All case studies →</a>
    </div>
    <div class="mt-8 grid md:grid-cols-2 gap-6">
        @foreach ($featuredStudies as $study)
            <a href="{{ route('case-studies.show', $study) }}" class="block rounded-xl border border-slate-200 p-6 hover:border-slate-900 hover:shadow-sm transition">
                <div class="text-sm text-slate-500">{{ $study->dealership_name }} · {{ $study->location }}</div>
                <h3 class="mt-2 text-xl font-bold">{{ $study->headline }}</h3>
                <p class="mt-3 text-slate-600 italic">&ldquo;{{ Str::limit($study->quote, 160) }}&rdquo;</p>
                <div class="mt-4 inline-flex items-baseline gap-2">
                    <span class="text-3xl font-bold">{{ $study->metric_value }}</span>
                    <span class="text-sm text-slate-500">{{ $study->metric_label }}</span>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="max-w-5xl mx-auto px-6 py-24 text-center">
    <h2 class="text-3xl font-bold">Ready to see what your cars could look like?</h2>
    <p class="mt-3 text-slate-600">Start a free trial — no credit card required.</p>
    <a href="{{ route('register') }}" class="mt-6 inline-flex items-center rounded-md bg-slate-900 text-white px-6 py-3 font-medium hover:bg-slate-700">Create account</a>
</section>
@endsection
