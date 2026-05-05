@props([
'name',
'label' => null,
'options' => [],
'value' => null,
'disabled' => false,
'optionSelect' => true,
])

@php
$selectedValue = old($name, $value);
$selectId = $attributes->get('id') ?? $name;
@endphp

<div>
    @if ($label)
    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
        {{ $label }}
    </label>
    @endif

    <select id="{{ $selectId }}" name="{{ $name }}" @if($disabled) disabled @endif {{ $attributes->merge([
        'class' => '
        tom-select w-full rounded-lg text-sm
        dark:text-white/90
        ' .

        ($disabled
        ? 'bg-gray-100 cursor-not-allowed dark:bg-gray-800 '
        : 'bg-transparent dark:bg-gray-900 '
        ) .

        ($errors->has($name)
        ? 'border border-red-500 focus:border-red-500 focus:ring-red-500/20'
        : 'border border-gray-300 focus:border-brand-300 focus:ring-brand-500/10')
        ]) }}
        >
        @if(!$optionSelect)
            <option value="">-- Pilih {{ $label }} --</option>
        @endif

        @foreach ($options as $key => $option)
        <option value="{{ $key }}" {{ (string)$selectedValue===(string)$key ? 'selected' : '' }}>
            {{ $option }}
        </option>
        @endforeach
    </select>

    @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

