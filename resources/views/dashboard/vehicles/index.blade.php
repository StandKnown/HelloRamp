<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Vehicles</h2>
            <a href="{{ route('vehicles.create') }}" class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">Add vehicle</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr>
                            <th class="text-left px-4 py-3">Vehicle</th>
                            <th class="text-left px-4 py-3">Stock</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Photos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($vehicles as $v)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $v->displayName() ?: 'Untitled vehicle' }}</div>
                                    <div class="text-xs text-slate-500">{{ $v->vin }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $v->stock_number ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block rounded px-2 py-0.5 text-xs bg-slate-100">{{ $v->status }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $v->photos->count() }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('vehicles.show', $v) }}" class="text-slate-700 hover:text-slate-900">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">No vehicles yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $vehicles->links() }}</div>
        </div>
    </div>
</x-app-layout>
