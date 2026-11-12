@extends('layouts.marketing')
@section('title', 'Social — Automated listings · HelloRamp')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <p class="uppercase tracking-widest text-xs text-slate-500 font-semibold">Social</p>
    <h1 class="text-5xl font-bold mt-3 tracking-tight">Your inventory on every channel, every day.</h1>
    <p class="mt-6 text-lg text-slate-600 max-w-2xl">
        Posts, reels, and Marketplace listings for every car, auto-generated and published daily across Instagram, TikTok, Facebook, YouTube, and Facebook Marketplace.
    </p>
    <div class="mt-10 grid md:grid-cols-5 gap-4 text-center">
        @foreach (['Instagram', 'TikTok', 'Facebook', 'YouTube', 'Marketplace'] as $channel)
            <div class="rounded-lg border border-slate-200 py-6 font-semibold">{{ $channel }}</div>
        @endforeach
    </div>
</section>
@endsection
