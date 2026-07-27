@props([
    'label'       => '',
    'name'        => '',
    'value'       => '',
    'placeholder' => '',
    'rows'        => 4,
    'required'    => false,
])

<div class="flex flex-col gap-1.5">
    @if ($label)
        <label for="{{ $name }}" class="text-sm font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'rounded-lg border px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 shadow-sm
                resize-none transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 '
                . ($errors->has($name)
                    ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400'
                    : 'border-slate-300 bg-white hover:border-slate-400')
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="flex items-center gap-1 text-xs text-red-600">
            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd" />
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>