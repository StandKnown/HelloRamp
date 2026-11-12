@php($s = $study ?? null)
@foreach ([
    ['slug', 'Slug (optional)'],
    ['dealership_name', 'Dealership name'],
    ['location', 'Location'],
    ['headline', 'Headline'],
    ['subheadline', 'Subheadline'],
    ['metric_value', 'Metric value (e.g. 25%)'],
    ['metric_label', 'Metric label'],
    ['quote_author', 'Quote author'],
    ['quote_author_role', 'Quote author role'],
] as [$f, $l])
    <div>
        <label class="block text-sm font-medium">{{ $l }}</label>
        <input name="{{ $f }}" value="{{ old($f, $s->{$f} ?? '') }}" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">
        @error($f) <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
    </div>
@endforeach
<div>
    <label class="block text-sm font-medium">Quote</label>
    <textarea name="quote" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">{{ old('quote', $s->quote ?? '') }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium">Body</label>
    <textarea name="body" rows="6" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm">{{ old('body', $s->body ?? '') }}</textarea>
</div>
<div class="flex items-center gap-4">
    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_published" value="0"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $s->is_published ?? true))> Published</label>
    <label class="inline-flex items-center gap-2 text-sm"><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $s->is_featured ?? false))> Featured</label>
</div>
