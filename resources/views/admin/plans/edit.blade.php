<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit plan</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.plans.update', $plan) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @method('PUT')
                @foreach ([
                    ['name', 'Name'],
                    ['tagline', 'Tagline'],
                    ['price_cents', 'Price (cents)'],
                    ['stripe_price_id', 'Stripe price ID'],
                    ['vehicle_limit', 'Vehicle limit'],
                    ['sort_order', 'Sort order'],
                ] as [$f, $l])
                    <div>
                        <label class="block text-sm font-medium">{{ $l }}</label>
                        <input name="{{ $f }}" value="{{ old($f, $plan->{$f}) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                        @error($f) <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                @endforeach
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active))> Active</label>
                    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $plan->is_featured))> Featured</label>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.plans.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm">Cancel</a>
                    <button class="rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
