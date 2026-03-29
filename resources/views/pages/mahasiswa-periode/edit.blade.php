@extends('starter')
@section('title', 'Edit Data Status Mahasiswa')
@section('content')
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Edit Data Status
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <form action="{{ route('mahasiswa-periode-semester.update', $mahasiswaPeriodeSemester) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6 mb-6">
                <x-form-input class="uppercase" name="kode" label="NRP dan Nama Mahasiswa" :value="$mahasiswaPeriodeSemester?->mahasiswa->nrp . ' ' . $mahasiswaPeriodeSemester?->mahasiswa->nama" :readonly="true"/>
                <x-form-select name="status" label="Status Mahasiswa"
                        :value="$mahasiswaPeriodeSemester?->status ?? '-' " :options="[
                            1 => 'Aktif',
                            0 => 'Cuti',
                            2 => 'Tanpa Kabar',
                            3 => 'Lulus'
                        ]" />
            </div>
            <div class="grip">
                <div>
                    <x-form-input name="deskripsi" label="Deskripsi" :value="$mahasiswaPeriodeSemester?->deskripsi"/>
                </div>
            </div>
            <div class="mt-10">
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Data Status Mahasiswa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
