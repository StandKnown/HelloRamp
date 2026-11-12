@extends('layouts.marketing')
@section('title', 'Contact us · HelloRamp')

@section('content')
<section class="max-w-2xl mx-auto px-6 py-20">
    <h1 class="text-4xl font-bold tracking-tight">Talk to our team.</h1>
    <p class="mt-3 text-slate-600">Tell us about your lot and we'll show you what your inventory could look like.</p>

    <form method="POST" action="{{ route('contact.store') }}" class="mt-10 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
            @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
            @error('email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Dealership</label>
            <input name="dealership" value="{{ old('dealership') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium">Message</label>
            <textarea name="message" rows="5" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">{{ old('message') }}</textarea>
            @error('message') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>
        <button class="inline-flex items-center rounded-md bg-slate-900 text-white px-6 py-3 font-medium hover:bg-slate-700">Send message</button>
    </form>
</section>
@endsection
