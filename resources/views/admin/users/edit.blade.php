<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit user</h2></x-slot>
    @include('admin._nav')

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white p-6 rounded-lg shadow-sm space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium">Role</label>
                    <select name="role" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                        @foreach (['admin', 'member'] as $r)
                            <option value="{{ $r }}" @selected(old('role', $user->role) === $r)>{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Dealership</label>
                    <select name="dealership_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
                        <option value="">— None —</option>
                        @foreach ($dealerships as $d)
                            <option value="{{ $d->id }}" @selected(old('dealership_id', $user->dealership_id) == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm">Cancel</a>
                    <button class="rounded-md bg-slate-900 text-white px-4 py-2 text-sm font-medium">Save</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?')">
                @csrf
                @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Delete user</button>
            </form>
        </div>
    </div>
</x-app-layout>
