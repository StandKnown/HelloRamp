<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Plans</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase"><tr><th class="text-left px-4 py-3">Name</th><th class="text-left px-4 py-3">Price</th><th class="text-left px-4 py-3">Stripe price ID</th><th class="text-left px-4 py-3">Active</th><th></th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach ($plans as $p)
                        <tr>
                            <td class="px-4 py-3">{{ $p->name }}</td>
                            <td class="px-4 py-3">{{ $p->priceFormatted() }}/mo</td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $p->stripe_price_id ?? '— not set —' }}</td>
                            <td class="px-4 py-3">{{ $p->is_active ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.plans.edit', $p) }}" class="underline">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
