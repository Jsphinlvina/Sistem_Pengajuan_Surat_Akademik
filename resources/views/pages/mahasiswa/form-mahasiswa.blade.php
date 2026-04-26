@php
$mahasiswa = $mahasiswa ?? null;
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
@endphp

@if($mode == 'create')
<div class="space-y-6 border-t border-gray-100 dark:border-gray-800">
    <div class="rounded-2xl border bg-white p-6 mt-8">
        <div class="justify-between flex items-center mb-4">
            <h3 class="text-base">Import Data Mahasiswa</h3>
            <a class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-gray-400 shadow-theme-xs hover:bg-gray-600"
                href="{{ route('mahasiswa.template.download') }}">
                Unduh Template
            </a>
        </div>
        <form action="{{ route('mahasiswa.import') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <x-form-upload name="file" label="Upload File Excel" accept=".xlsx,.xls,.csv" />
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                    Import Data Mahasiswa
                </button>
            </div>
        </form>
        @if(session('preview_mahasiswa'))
        <form action="{{ route('mahasiswa.store') }}"
            method="POST">
            @csrf
            <input type="hidden" name="data" value='@json(session("preview_mahasiswa"))'>
            <x-table>
                <x-slot:title> Preview Data Mahasiswa</x-slot:title>
                <x-slot:head>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        @foreach(['No','NRP', 'Nama'] as $column)
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
                    @foreach(session('preview_mahasiswa') as $mahasiswa)
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
                                    {{ $mahasiswa['nrp'] }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 py-4 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-gray-500 capitalize text-theme-sm dark:text-gray-400">
                                    {{ $mahasiswa['nama'] }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-slot>
            </x-table>
            <button type="submit" class="px-4 mt-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                Simpan Data Mahasiswa
            </button>
        </form>
        @endif
    </div>
</div>

@elseif($isEdit)
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Edit Data Mahasiswa
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">

        <form action="{{ route('mahasiswa.update', $mahasiswa)}}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">
            <div>
                <x-form-input name="nrp" label="NRP" :value="$mahasiswa?->nrp" :readonly="$isEdit"
                    placeholder="72" />
            </div>
            <div>
                <x-form-input name="name" label="Nama Lengkap Mahasiswa" :value="$mahasiswa?->name"
                    placeholder="John Doe" />
            </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mt-6">
                <div>
                    <x-form-input name="email" type="email" label="Email" :value="$mahasiswa?->email" placeholder="2272001@maranatha.ac.id" />
                </div>
                <div>
                    <x-form-input name="tahun_lulus" type="date" label="Tahun Lulus" :value="$mahasiswa?->tahun_lulus" placeholder="" />
                </div>
            </div>
            <div class="grid mt-6">
                <x-form-input name="alamat" label="Alamat" :value="$mahasiswa?->alamat" placeholder="Jl. Surya Sumantri No. 65" />
            </div>
            <div class="mt-10">
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Data Mahasiswa
                </button>
            </div>
        </form>
    </div>
</div>
@endif
