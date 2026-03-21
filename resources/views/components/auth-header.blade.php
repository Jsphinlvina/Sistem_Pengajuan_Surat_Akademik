@props([
    'title',
    'description',
])
<div class="mb-5 sm:mb-8">
    <flux:heading class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white">
        {{ $title }}
    </flux:heading>
    <flux:subheading class="text-sm text-gray-500">
        {{ $description }}
    </flux:subheading>
</div>
