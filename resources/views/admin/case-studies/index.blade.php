<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Case studies</h2>
            <a href="{{ route('admin.case-studies.create') }}" class="inline-flex items-center rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium">New</a>
        </div>
    </x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase"><tr><th class="text-left px-4 py-3">Headline</th><th class="text-left px-4 py-3">Dealership</th><th class="text-left px-4 py-3">Published</th><th></th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                    @foreach ($studies as $s)
                        <tr>
                            <td class="px-4 py-3">{{ $s->headline }}</td>
                            <td class="px-4 py-3">{{ $s->dealership_name }}</td>
                            <td class="px-4 py-3">{{ $s->is_published ? 'Yes' : 'No' }}</td>
                            <td class="px-4 py-3 text-right"><a href="{{ route('admin.case-studies.edit', $s) }}" class="underline">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $studies->links() }}</div>
        </div>
    </div>
</x-app-layout>
