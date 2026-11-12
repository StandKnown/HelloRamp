<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">New case study</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.case-studies.store') }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @include('admin.case-studies._form', ['study' => null])
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.case-studies.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm">Cancel</a>
                    <button class="rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium">Create</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
