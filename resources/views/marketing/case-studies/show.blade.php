@extends('layouts.marketing')
@section('title', $study->headline.' · HelloRamp')

@section('content')
<article class="max-w-3xl mx-auto px-6 py-20">
    <div class="text-sm text-slate-500">{{ $study->dealership_name }} · {{ $study->location }}</div>
    <h1 class="text-4xl font-bold tracking-tight mt-3">{{ $study->headline }}</h1>
    @if ($study->subheadline)
        <p class="mt-3 text-lg text-slate-600">{{ $study->subheadline }}</p>
    @endif

    @if ($study->metric_value)
        <div class="mt-8 rounded-xl bg-slate-900 text-white p-8">
            <div class="text-6xl font-bold">{{ $study->metric_value }}</div>
            <div class="mt-1 text-slate-300">{{ $study->metric_label }}</div>
        </div>
    @endif

    @if ($study->quote)
        <blockquote class="mt-10 border-l-4 border-slate-900 pl-6">
            <p class="text-xl italic">&ldquo;{{ $study->quote }}&rdquo;</p>
            <footer class="mt-3 text-sm text-slate-500">— {{ $study->quote_author }}, {{ $study->quote_author_role }}</footer>
        </blockquote>
    @endif

    @if ($study->body)
        <div class="mt-10 prose prose-slate max-w-none">
            {!! nl2br(e($study->body)) !!}
        </div>
    @endif

    <div class="mt-16 border-t border-slate-200 pt-10 text-center">
        <h2 class="text-2xl font-bold">Could your lot do the same?</h2>
        <a href="{{ route('register') }}" class="mt-4 inline-flex items-center rounded-md bg-slate-900 text-white px-6 py-3 font-medium hover:bg-slate-700">Start free trial</a>
    </div>
</article>
@endsection
