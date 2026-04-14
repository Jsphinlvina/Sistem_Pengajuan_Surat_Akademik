@props([
'name',
'label' => null,
'accept'
])

<div class="space-y-6 border-t border-gray-100 py-5 sm:py-6 dark:border-gray-800">
    <div>
        @if($label)
          <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            {{$label}}
          </label>
        @endif
          <input name="{{ $name }}" type="file"  accept="{{ $accept }}}" class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400"/>
        @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
