@php($v = $vehicle ?? null)

@foreach ([
    ['vin', 'VIN'],
    ['stock_number', 'Stock number'],
    ['year', 'Year'],
    ['make', 'Make'],
    ['model', 'Model'],
    ['trim', 'Trim'],
    ['exterior_color', 'Exterior color'],
] as [$field, $label])
    <div>
        <label class="block text-sm font-medium">{{ $label }}</label>
        <input name="{{ $field }}" value="{{ old($field, $v->{$field} ?? '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
        @error($field) <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>
@endforeach

<div>
    <label class="block text-sm font-medium">Status</label>
    <select name="status" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
        @foreach (['draft', 'processing', 'ready', 'live', 'archived'] as $s)
            <option value="{{ $s }}" @selected(old('status', $v->status ?? 'draft') === $s)>{{ $s }}</option>
        @endforeach
    </select>
</div>
