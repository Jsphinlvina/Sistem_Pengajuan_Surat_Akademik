@php
$kurikulum = $kurikulum ?? null;
$mode = $mode ?? 'create';

$isShow = $mode === 'show';
$isEdit = $mode === 'edit';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $mode === 'create' ? 'Tambah Kurikulum' : ($mode === 'edit' ? 'Edit Kurikulum' : 'Detail Kurikulum') }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <form action="{{
                $mode === 'create'
                    ? route('kurikulum.store')
                    : ($mode === 'edit' ? route('kurikulum.update', $kurikulum) : '#')
            }}" method="POST">
            @csrf
            @if($isEdit)
            @method('PUT')
            @endif
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <x-form-input name="nama" label="Nama Kurikulum" :value="$kurikulum?->nama" :readonly="$isShow"
                        placeholder="Kurikulum Teknik Informatika 2026" />
                </div>
                <div>
                    @if($isEdit || $isShow)
                    <x-form-select name="status" label="Status Kurikulum" :disabled="$isShow"
                        :value="$kurikulum?->status ?? 'staff' " :options="[
                            1 => 'Aktif',
                            0 => 'Tidak Aktif'
                        ]" />
                    @endif
                </div>

            </div>
            <div class="mt-10">
                @if($mode === 'create')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Tambahkan Kurikulum
                </button>

                @elseif($mode === 'edit')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Kurikulum
                </button>
                @else
                <a href="{{ route('kurikulum.edit', $kurikulum) }}"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Edit Kurikulum
                </a>
                <x-comfirm-delete :action="route('kurikulum.destroy', $kurikulum->id)" message="Yakin ingin menghapus Kurikulum ini?">
                          <x-slot:trigger>
                            <a class="ms-4 px-4 py-3 text-sm font-medium text-white rounded-lg bg-error-500 hover:bg-error-600">
                                Hapus Kurikulum
                             </a>
                          </x-slot:trigger>
                       </x-comfirm-delete>
                @endif
            </div>
        </form>
        @if($isEdit)
        <div class="rounded-2xl border bg-white p-6 mt-8">
            <h3 class="text-base mb-4">
                Import Data Mata Kuliah
            </h3>
            <form action="{{ route('kurikulum.mata-kuliah.import', $kurikulum) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <x-form-upload name="file" label="Upload File Excel" />
                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                        Import Mata Kuliah
                    </button>
                </div>
            </form>
        </div>
        @endif
        @if(session('preview_mk'))
        <div class="rounded-2xl border bg-yellow-50 p-6 mt-8">
            <form action="{{ route('kurikulum.mata-kuliah.store-import', $kurikulum) }}" method="POST">
                @csrf
                <input type="hidden" name="data" value='@json(session("preview_mk"))'>
                <x-table>
                    <x-slot:title> Preview Data Mata Kuliah</x-slot:title>
                    <x-slot:head>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            @foreach(['No','Kode', 'Nama Mata Kuliah'] as $column)
                            <th class="px-5 py-3 sm:px-6">
                                <div class="flex items-center">
                                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                        {{ $column }}
                                    </p>
                                </div>
                            </th>
                            @endforeach
                        </tr>
                    </x-slot:head>
                    <x-slot>
                        @foreach(session('preview_mk') as $mk)
                        <tr class="border-b odd:bg-gray-100">
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $loop->iteration }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $mk['kode'] }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $mk['nama'] }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </x-slot>
                </x-table>
                <button type="submit" class="px-4 mt-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                    Simpan Mata Kuliah
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

@if($isShow)
<div class="space-y-6 border-t border-gray-100 dark:border-gray-800">
    <x-table>
        <x-slot:title>Daftar Mata Kuliah</x-slot:title>
        <x-slot:head>
            @foreach(['No','Kode','Nama Mata Kuliah','Action'] as $column)
            <th class="px-5 py-3 sm:px-6">
                <div class="flex items-center">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                        {{ $column }}
                    </p>
                </div>
            </th>
            @endforeach
        </x-slot:head>
        <x-slot>
            @foreach($kurikulum->mataKuliah as $mataKuliah)
            <tr class="odd:bg-gray-100">
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            {{$loop->iteration}}.
                        </p>
                    </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            {{$mataKuliah->kode}}
                        </p>
                    </div>
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            {{$mataKuliah->nama}}
                        </p>
                    </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                       <a href="{{ route('mata-kuliah.edit', $mataKuliah) }}">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             class="stroke-gray-500 me-4 pb-1" stroke="currentColor" width="20" height="20">
                             <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                          </svg>
                       </a>
                       <x-comfirm-delete :action="route('mata-kuliah.destroy', $mataKuliah->id)" message="Yakin ingin menghapus Mata Kuliah ini?">
                          <x-slot:trigger>
                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" width="17" height="17" class="stroke-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                   d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                             </svg>
                          </x-slot:trigger>
                       </x-comfirm-delete>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-slot>
    </x-table>
</div>
@endif
