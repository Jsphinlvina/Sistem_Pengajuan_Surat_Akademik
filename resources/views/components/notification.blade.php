@props([
'type' => 'success',
'title' => null,
'message' => null,
'link' => null,
'linkText' => 'Learn more',
])

@php
$variants = [
'success' => [
'border' => 'border-success-500',
'bg' => 'bg-success-50 dark:bg-success-500/15 dark:border-success-500/30',
'text' => 'text-success-500',
'icon' => 'check',
],
'error' => [
'border' => 'border-error-500',
'bg' => 'bg-error-50 dark:bg-error-500/15 dark:border-error-500/30',
'text' => 'text-error-500',
'icon' => 'error',
],
'info' => [
'border' => 'border-blue-light-500',
'bg' => 'bg-blue-light-50 dark:bg-blue-light-500/15 dark:border-blue-light-500/30',
'text' => 'text-blue-light-500',
'icon' => 'info',
],
'warning' => [
'border' => 'border-warning-500',
'bg' => 'bg-warning-50 dark:bg-warning-500/15 dark:border-warning-500/30',
'text' => 'text-warning-500 dark:text-orange-400',
'icon' => 'warning',
],
];

$variant = $variants[$type] ?? $variants['success'];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="rounded-xl border p-4 {{ $variant['border'] }} {{ $variant['bg'] }} mb-2 mt-5 mx-7">
    <div class="flex items-start gap-3">
        <div class="-mt-0.5 {{ $variant['text'] }}">
            @if ($variant['icon'] === 'check')
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 1.9C6.42 1.9 1.9 6.42 1.9 12S6.42 22.1 12 22.1 22.1 17.58 22.1 12 17.58 1.9 12 1.9Zm-1.1 12.63L7.9 11.53a.9.9 0 1 1 1.27-1.27l1.73 1.73 3.78-3.78a.9.9 0 1 1 1.27 1.27l-5.05 5.05a.9.9 0 0 1-1.27 0Z" />
            </svg>
            @elseif ($variant['icon'] === 'error')
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 1.85C6.39 1.85 1.85 6.39 1.85 12S6.39 22.15 12 22.15 22.15 17.61 22.15 12 17.61 1.85 12 1.85Zm0 14.62a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm.75-3.42a.75.75 0 0 1-1.5 0V7.38a.75.75 0 0 1 1.5 0v5.67Z" />
            </svg>
            @elseif ($variant['icon'] === 'info')
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 1.85C6.39 1.85 1.85 6.39 1.85 12S6.39 22.15 12 22.15 22.15 17.61 22.15 12 17.61 1.85 12 1.85Zm0 6.67a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm.75 8.1a.75.75 0 0 1-1.5 0v-5.67a.75.75 0 0 1 1.5 0v5.67Z" />
            </svg>
            @elseif ($variant['icon'] === 'warning')
            <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 1.85C6.39 1.85 1.85 6.39 1.85 12S6.39 22.15 12 22.15 22.15 17.61 22.15 12 17.61 1.85 12 1.85Zm0 6.67a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm.75 8.1a.75.75 0 0 1-1.5 0v-5.67a.75.75 0 0 1 1.5 0v5.67Z" />
            </svg>
            @endif
        </div>
        <div>
            @if ($title)
            <h4 class="mb-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                {{ $title }}
            </h4>
            @endif

            @if ($message)
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $message }}
            </p>
            @endif

            @if ($link)
            <a href="{{ $link }}"
                class="mt-3 inline-block text-sm font-medium text-gray-500 underline dark:text-gray-400">
                {{ $linkText }}
            </a>
            @endif
        </div>
    </div>
</div>
