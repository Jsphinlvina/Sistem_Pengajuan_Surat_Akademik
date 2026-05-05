@extends('starter')
@section('title', 'Proses Surat')

@section('content')
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3 mb-5 flex justify-between items-center">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="font-bold">
           Status Pengajuan
        </h3>
    </div>
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <x-comfirm-delete :action="route('pengajuan.reject', $pengajuan->id)"
              message="Yakin ingin tolak Pengajuan ini?" title="Tolak Pengajuan" button-text="Tolak" method="POST">
              <x-slot:trigger>
                 <x-danger-button>Tolak Pengajuan</x-danger-button>
              </x-slot:trigger>
        </x-comfirm-delete>
    </div>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="font-bold">
           {{ $template->nama }} | {{$pengajuan->mahasiswa->nrp}} - {{$pengajuan->mahasiswa->name}}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
            <form action="{{ route('pengajuan.lhsm.preview', $pengajuan->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <x-form-upload name="file" label="Upload File PDF " accept="application/pdf" required />
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg">
                    Upload & Preview
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
