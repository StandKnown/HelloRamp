@extends('layouts.marketing')
@section('title', 'Solutions · HelloRamp')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <h1 class="text-5xl font-bold tracking-tight">Built for every size of operation.</h1>
    <div class="mt-12 grid md:grid-cols-3 gap-6">
        @foreach ([
            ['Single-lot dealers', 'Stand out in your market with premium photos that don\'t look like every other listing.'],
            ['Dealer groups', 'Roll out a consistent visual standard across every rooftop. Centralized reporting.'],
            ['Online platforms', 'Bulk-process inventory ingested from dealer partners. DMS integrations included.'],
        ] as [$title, $body])
            <div class="rounded-xl border border-slate-200 p-6">
                <div class="font-bold text-lg">{{ $title }}</div>
                <p class="mt-2 text-slate-600 text-sm">{{ $body }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-16">
        <h2 class="text-2xl font-bold">DMS integrations</h2>
        <div class="mt-4 flex flex-wrap gap-3 text-sm">
            @foreach (['vAuto', 'DealerCenter', 'Dealer.com', 'DealerOn'] as $dms)
                <span class="rounded-full border border-slate-300 px-4 py-2">{{ $dms }}</span>
            @endforeach
        </div>
    </div>
</section>
@endsection
