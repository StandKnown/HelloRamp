<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit vehicle</h2></x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @method('PUT')
                @include('dashboard.vehicles._fields', ['vehicle' => $vehicle])
                <div class="flex justify-end gap-2">
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm">Cancel</a>
                    <button class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">Save</button>
                </div>
            </form>

            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Delete this vehicle and its photos?')">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Delete vehicle</button>
            </form>
        </div>
    </div>
</x-app-layout>
