@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'readonly' => false,
])

<div>
    @if ($label)
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if($readonly) readonly @endif
        {{ $attributes->merge([
            'class' => '
                h-11 w-full rounded-lg px-4 py-2.5 text-sm
                dark:text-white/90
                ' .

                ($readonly
                    ? 'bg-gray-100 cursor-not-allowed dark:bg-gray-800 '
                    : 'bg-transparent dark:bg-gray-900 '
                ) .

                ($errors->has($name)
                    ? 'border border-red-500 focus:border-red-500 focus:ring-red-500/20'
                    : 'border border-gray-300 focus:border-brand-300 focus:ring-brand-500/10')
        ]) }}
    >

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
