<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <div class="grid md:grid-cols-4 gap-4">
                @foreach ([
                    ['Vehicles', $vehicleCount],
                    ['Live listings', $liveVehicleCount],
                    ['Processing', $processingCount],
                    ['Ready / approved', $readyCount],
                ] as [$label, $value])
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="text-sm text-slate-500">{{ $label }}</div>
                        <div class="mt-2 text-3xl font-bold">{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold">Recent vehicles</h3>
                    <a href="{{ route('vehicles.create') }}" class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">Add vehicle</a>
                </div>
                <ul class="mt-4 divide-y divide-slate-100">
                    @forelse ($recentVehicles as $v)
                        <li class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium">{{ $v->displayName() ?: 'Untitled vehicle' }}</div>
                                <div class="text-xs text-slate-500">Stock {{ $v->stock_number ?? '—' }} · {{ $v->status }}</div>
                            </div>
                            <a href="{{ route('vehicles.show', $v) }}" class="text-sm text-slate-600 hover:text-slate-900">View →</a>
                        </li>
                    @empty
                        <li class="py-6 text-sm text-slate-500">No vehicles yet. <a href="{{ route('vehicles.create') }}" class="underline">Add your first one.</a></li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold">Quick links</h3>
                <div class="mt-3 flex gap-3 flex-wrap text-sm">
                    <a href="{{ route('vehicles.index') }}" class="rounded-md border border-slate-300 px-4 py-2 hover:bg-slate-50">All vehicles</a>
                    <a href="{{ route('billing.index') }}" class="rounded-md border border-slate-300 px-4 py-2 hover:bg-slate-50">Billing</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="rounded-md border border-slate-300 px-4 py-2 hover:bg-slate-50">Admin</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
