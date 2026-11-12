<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Review queue</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <div class="grid md:grid-cols-3 gap-4">
                @forelse ($photos as $photo)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="aspect-video bg-slate-100 grid place-items-center text-slate-400 text-xs">
                            @if ($photo->enhanced_path)
                                <img src="{{ asset('storage/'.$photo->enhanced_path) }}" alt="" class="w-full h-full object-cover">
                            @else
                                {{ $photo->status }}
                            @endif
                        </div>
                        <div class="p-4 text-sm space-y-2">
                            <div class="font-medium">{{ $photo->vehicle->displayName() ?: 'Vehicle #'.$photo->vehicle_id }}</div>
                            <div class="text-xs text-slate-500">{{ $photo->vehicle->dealership?->name }} · rev {{ $photo->revision }}</div>
                            <div class="flex gap-2 pt-2">
                                <form method="POST" action="{{ route('admin.review-queue.approve', $photo) }}">
                                    @csrf
                                    <button class="rounded-md bg-emerald-600 text-white px-3 py-1 text-xs font-medium">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.review-queue.reject', $photo) }}">
                                    @csrf
                                    <button class="rounded-md border border-red-300 text-red-600 px-3 py-1 text-xs font-medium">Reject + reprocess</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-sm p-10 text-center text-slate-500">No photos in queue.</div>
                @endforelse
            </div>

            <div class="mt-4">{{ $photos->links() }}</div>
        </div>
    </div>
</x-app-layout>
