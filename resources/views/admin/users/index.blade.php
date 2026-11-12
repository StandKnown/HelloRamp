<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2 text-sm">{{ session('status') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                        <tr><th class="text-left px-4 py-3">Name</th><th class="text-left px-4 py-3">Email</th><th class="text-left px-4 py-3">Role</th><th class="text-left px-4 py-3">Dealership</th><th></th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $u)
                            <tr>
                                <td class="px-4 py-3">{{ $u->name }}</td>
                                <td class="px-4 py-3">{{ $u->email }}</td>
                                <td class="px-4 py-3">{{ $u->role }}</td>
                                <td class="px-4 py-3">{{ $u->dealership?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('admin.users.edit', $u) }}" class="underline">Edit</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
