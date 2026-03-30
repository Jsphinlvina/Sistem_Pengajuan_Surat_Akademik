@php
$periodeSemester = $periodeSemester ?? null;
$mode = $mode ?? 'create';

$isShow = $mode === 'show';
$isEdit = $mode === 'edit';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $mode === 'create' ? 'Tambah Periode Semester' : ($mode === 'edit' ? 'Edit Periode Semester' : 'Detail
            Periode Semester') }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <form action="{{
                $mode === 'create'
                    ? route('periode-semester.store')
                    : ($mode === 'edit' ? route('periode-semester.update', $periodeSemester) : '#')
            }}" method="POST">
            @csrf
            @if($isEdit)
            @method('PUT')
            @endif
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <x-form-input name="nama" label="Nama Periode Semester" :value="$periodeSemester?->nama"
                        :readonly="$isShow" placeholder="Reguler Ganjil 2025/2026" />
                </div>
                <div>
                    @if($isEdit || $isShow)
                    <x-form-select name="status" label="Status Periode Semester" :disabled="$isShow"
                        :value="$periodeSemester?->status ?? '-' " :options="[
                            1 => 'Aktif',
                            0 => 'Tidak Aktif'
                        ]" />
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <x-form-input name="kaprodi" label="Nama Kepala Program Studi" :value="$periodeSemester?->kaprodi"
                        :readonly="$isShow" placeholder="John Doe" />
                </div>
            </div>
            <div class="mt-10">
                @if($mode === 'create')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Tambahkan Periode Semester
                </button>

                @elseif($mode === 'edit')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Periode Semester
                </button>
                @else
                <a href="{{ route('periode-semester.edit', $periodeSemester) }}"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Edit Periode Semester
                </a>
                <x-comfirm-delete :action="route('periode-semester.destroy', $periodeSemester->id)"
                    message="Yakin ingin menghapus Periode Semester ini?">
                    <x-slot:trigger>
                        <a
                            class="ms-4 px-4 py-3 text-sm font-medium text-white rounded-lg bg-error-500 hover:bg-error-600">
                            Hapus Periode Semester
                        </a>
                    </x-slot:trigger>
                </x-comfirm-delete>
                @endif
            </div>
        </form>
    </div>
</div>

@if($isEdit)
<div class="space-y-6 border-t border-gray-100 dark:border-gray-800">
    <div class="rounded-2xl border bg-white p-6 mt-8">
        <div class="justify-between flex items-center mb-4">
            <h3 class="text-base">Import Data Status Mahasiswa</h3>
            <a class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-gray-400 shadow-theme-xs hover:bg-gray-600"
                href="{{ route('mahasiswa-periode-semester.template.download') }}">
                Unduh Template
            </a>
        </div>
        <form action="{{ route('periode-semester.mahasiswa-periode-semester.import', $periodeSemester) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <x-form-upload name="file" label="Upload File Excel" />
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                    Import Data Status Mahasiswa
                </button>
            </div>
        </form>
        @if(session('preview_mahasiswa_periode'))
        <form action="{{ route('periode-semester.mahasiswa-periode-semester.store-import', $periodeSemester) }}"
            method="POST">
            @csrf
            <input type="hidden" name="data" value='@json(session("preview_mahasiswa_periode"))'>
            <x-table>
                <x-slot:title> Preview Data Status Mahasiswa</x-slot:title>
                <x-slot:head>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        @foreach(['No','NRP', 'Status'] as $column)
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
                    @foreach(session('preview_mahasiswa_periode') as $mp)
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
                                    {{ $mp['nrp'] }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 capitalize text-theme-sm dark:text-gray-400">
                                    {{ \App\Models\MahasiswaPeriodeSemester::mapping[$mk['status']] ?? 'Tidak ada' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-slot>
            </x-table>
            <button type="submit" class="px-4 mt-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                Simpan Data Status Mahasiswa
            </button>
        </form>
        @endif
    </div>
</div>
@endif

@if($isShow)
<div class="space-y-6 border-t border-gray-100 dark:border-gray-800">
    <x-table>
        <x-slot:title>Daftar Status Mahasiswa</x-slot:title>
        <x-slot:head>
            @foreach(['No','NRP', 'Nama', 'Status','Action'] as $column)
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
            @foreach($periodeSemester->mahasiswaPeriodeSemester as $mahasiswaPeriodeSemester)
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
                            {{$mahasiswaPeriodeSemester->mahasiswa->nrp}}
                        </p>
                    </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            {{$mahasiswaPeriodeSemester->mahasiswa->nama}}
                        </p>
                    </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                            {{$mahasiswaPeriodeSemester->status_text}}
                        </p>
                    </div>
                </td>
                <td class="px-5 py-4 sm:px-6">
                    <div class="flex items-center">
                        <a href="{{ route('mahasiswa-periode-semester.edit', $mahasiswaPeriodeSemester) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                class="stroke-gray-500 me-4 pb-1" stroke="currentColor" width="20" height="20">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </a>
                        <x-comfirm-delete
                            :action="route('mahasiswa-periode-semester.destroy', $mahasiswaPeriodeSemester->id)"
                            message="Yakin ingin menghapus Data Status Mahasiswa ini?">
                            <x-slot:trigger>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" width="17" height="17"
                                    class="stroke-gray-500">
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
