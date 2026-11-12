<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Add vehicle</h2></x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('vehicles.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @include('dashboard.vehicles._fields', ['vehicle' => null])
                <div class="flex justify-end gap-2">
                    <a href="{{ route('vehicles.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm">Cancel</a>
                    <button class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">Create vehicle</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
