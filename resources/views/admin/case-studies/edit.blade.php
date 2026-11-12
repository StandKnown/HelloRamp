<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit case study</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.case-studies.update', $study) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @method('PUT')
                @include('admin.case-studies._form', ['study' => $study])
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.case-studies.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm">Back</a>
                    <button class="rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium">Save</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.case-studies.destroy', $study) }}" onsubmit="return confirm('Delete this case study?')">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Delete case study</button>
            </form>
        </div>
    </div>
</x-app-layout>
