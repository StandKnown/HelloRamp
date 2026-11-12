<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Admin dashboard</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($stats as $label => $value)
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-sm text-slate-500 capitalize">{{ str_replace('_', ' ', $label) }}</div>
                        <div class="mt-2 text-3xl font-bold">{{ $value }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
