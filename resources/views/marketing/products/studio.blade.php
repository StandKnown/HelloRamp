@extends('layouts.marketing')
@section('title', 'Studio — AI vehicle photography · HelloRamp')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <p class="uppercase tracking-widest text-xs text-slate-500 font-semibold">Studio</p>
    <h1 class="text-5xl font-bold mt-3 tracking-tight">Phone in. Premium listing out.</h1>
    <p class="mt-6 text-lg text-slate-600 max-w-2xl">
        Take photos of any vehicle on a lot — or hand us the ones you already have. We standardize the angles, clean the background, remove reflections off glossy paint, and deliver studio-quality images, 360° spins, and short video tours.
    </p>
</section>

<section class="max-w-7xl mx-auto px-6 pb-20 grid md:grid-cols-2 gap-8">
    @foreach ([
        ['Clean backgrounds', 'Cluttered lots disappear. Every car on the same calm backdrop.'],
        ['Reflection cleanup', 'Glossy paint picks up everything. We remove the showroom, the ceiling, the phone in the shot.'],
        ['Consistent angles', 'Every listing uses the same shot list. Dealers look like national brands.'],
        ['360° spins', 'Interactive walkaround generated from a handful of still photos.'],
        ['AI Walkaround videos', 'Presenter-led video tour built from 4–8 photos.'],
        ['Human quality check', 'HelloTrust reviews every asset before it goes live.'],
    ] as [$title, $body])
        <div class="rounded-xl border border-slate-200 p-6">
            <div class="font-bold text-lg">{{ $title }}</div>
            <p class="mt-2 text-slate-600 text-sm">{{ $body }}</p>
        </div>
    @endforeach
</section>

<section class="bg-slate-50">
    <div class="max-w-5xl mx-auto px-6 py-20 text-center">
        <h2 class="text-3xl font-bold">1–5 days of photography → under 5 minutes.</h2>
        <p class="mt-3 text-slate-600 max-w-2xl mx-auto">
            Capture time per vehicle drops by over 99%. Arrival-to-live becomes same-day. Your most profitable inventory goes online first.
        </p>
        <a href="{{ route('register') }}" class="mt-8 inline-flex items-center rounded-md bg-slate-900 text-white px-6 py-3 font-medium hover:bg-slate-700">Try Studio free</a>
    </div>
</section>
@endsection
