@php
$programStudis = $programStudis ?? null;
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $mode === 'create' ? 'Tambah Program Studi' : ($mode === 'edit' ? 'Edit Program Studi' : 'Detail Program
            Studi') }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">

        <form action="{{
                $mode === 'create'
                    ? route('program-studi.store')
                    : ($mode === 'edit' ? route('program-studi.update', $programStudis) : '#')
            }}" method="POST">
            @csrf
            @if($isEdit)
            @method('PUT')
            @endif
            <div class="grid grid-cols-2 gap-6">
            <div>
                <x-form-input name="kode" label="Kode Program Studi" :value="$programStudis?->kode" :readonly="$isEdit"
                    placeholder="72" />
            </div>
            <div>
                <x-form-input name="nama" label="Nama Program Studi" :value="$programStudis?->nama"
                    placeholder="Teknik Informatika" />
            </div>
            </div>
            <div class="mt-10">
                @if($mode === 'create')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Tambahkan Program Studi
                </button>

                @elseif($mode === 'edit')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Program Studi
                </button>
                @endif
            </div>

        </form>
    </div>
</div>
