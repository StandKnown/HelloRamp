<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $vehicle->displayName() ?: 'Untitled vehicle' }}</h2>
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="text-sm font-medium text-slate-700 hover:text-slate-900">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold">Details</h3>
                <dl class="mt-4 grid md:grid-cols-3 gap-4 text-sm">
                    <div><dt class="text-slate-500">VIN</dt><dd>{{ $vehicle->vin ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Stock #</dt><dd>{{ $vehicle->stock_number ?? '—' }}</dd></div>
                    <div><dt class="text-slate-500">Status</dt><dd>{{ $vehicle->status }}</dd></div>
                    <div><dt class="text-slate-500">Color</dt><dd>{{ $vehicle->exterior_color ?? '—' }}</dd></div>
                </dl>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold">Upload photos</h3>
                </div>
                <form method="POST" action="{{ route('vehicles.photos.store', $vehicle) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium">Angle (optional)</label>
                        <select name="angle" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                            <option value="">— Choose angle —</option>
                            @foreach (config('photo.angles') as $slug => $label)
                                <option value="{{ $slug }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Photos</label>
                        <input type="file" name="photos[]" multiple accept="image/*" class="mt-1 block w-full text-sm">
                        @error('photos') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                        @error('photos.0') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>
                    <button class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium hover:bg-slate-700">Upload & process</button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-semibold">Photos ({{ $vehicle->photos->count() }})</h3>
                <div class="mt-4 grid md:grid-cols-3 gap-4">
                    @forelse ($vehicle->photos as $photo)
                        <div class="border border-slate-200 rounded-lg overflow-hidden">
                            <div class="aspect-video bg-slate-100 grid place-items-center text-slate-400 text-xs">
                                @if ($photo->enhanced_path)
                                    <img src="{{ asset('storage/'.$photo->enhanced_path) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ $photo->status }}
                                @endif
                            </div>
                            <div class="p-3 text-sm">
                                <div class="font-medium">{{ config('photo.angles.'.$photo->angle, $photo->angle ?? 'Photo') }}</div>
                                <div class="text-xs text-slate-500">rev {{ $photo->revision }} · {{ $photo->status }}</div>
                                <div class="mt-2 flex gap-2 text-xs">
                                    <form method="POST" action="{{ route('photos.revisit', $photo) }}">
                                        @csrf
                                        <button class="underline">Revisit</button>
                                    </form>
                                    <form method="POST" action="{{ route('photos.destroy', $photo) }}" onsubmit="return confirm('Delete this photo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 underline">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-sm text-slate-500">No photos yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
