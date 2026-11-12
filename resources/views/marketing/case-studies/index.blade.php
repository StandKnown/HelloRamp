@extends('layouts.marketing')
@section('title', 'Case studies · HelloRamp')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <h1 class="text-5xl font-bold tracking-tight">Dealers who moved faster with HelloRamp.</h1>
    <div class="mt-10 grid md:grid-cols-2 gap-6">
        @foreach ($studies as $study)
            <a href="{{ route('case-studies.show', $study) }}" class="block rounded-xl border border-slate-200 p-6 hover:border-slate-900 hover:shadow-sm transition">
                <div class="text-sm text-slate-500">{{ $study->dealership_name }} · {{ $study->location }}</div>
                <h3 class="mt-2 text-xl font-bold">{{ $study->headline }}</h3>
                <p class="mt-3 text-slate-600 italic">&ldquo;{{ \Illuminate\Support\Str::limit($study->quote, 180) }}&rdquo;</p>
                <div class="mt-4 inline-flex items-baseline gap-2">
                    <span class="text-3xl font-bold">{{ $study->metric_value }}</span>
                    <span class="text-sm text-slate-500">{{ $study->metric_label }}</span>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endsection
