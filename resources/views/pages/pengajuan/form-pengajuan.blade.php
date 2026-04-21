@php
$user = $user ?? null;
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';

$template = $template ?? null;
$formFields = $formFields ?? [];
$showFields = $showFields ?? [];
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="font-bold">
            {{ $template->nama }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <form action="{{ route('pengajuan.store') }}" method="POST">

            @csrf
            <input type="hidden" name="template_surat_id" value="{{ $template->id }}">
            @foreach($showFields as $key => $value)
                <div class="mt-3">
                    <x-form-input
                        name="show[{{ $key }}]"
                        label="{{ ucwords(str_replace('_',' ',$key)) }}"
                        :value="$value"
                        readonly
                    />
                </div>
            @endforeach
            @if ($template->id == 2)
            <div class="mt-3">
                <x-form-select
                    name="fields[kode_mata_kuliah]"
                    label="Mata Kuliah"
                    :options="$mataKuliahOptions"
                    :optionSelect="false"
                />
            </div>
            @endif
            @foreach($formFields as $field)
                <div class="mt-3">
                    <x-form-input
                        name="fields[{{ $field }}]"
                        label="{{ ucwords(str_replace('_',' ',$field)) }}"
                    />
                </div>
            @endforeach
            <div class="mt-10">
                <button
                    type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Ajukan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@if ($template->id == 2)
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            document.querySelectorAll('.tom-select').forEach(el => {
                if (!el.tomselect) {
                    new TomSelect(el);
                }
            });
        }, 0);
    });
</script>
@endif
