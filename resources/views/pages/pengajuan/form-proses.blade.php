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
        <x-confirm-accept :action="route('pengajuan.accept', $pengajuan->id)"
              message="Yakin ingin menyetujui Pengajuan ini?" title="Setujui Pengajuan">
              <x-slot:trigger>
                 <x-accept-button>Setujui Pengajuan</x-accept-button>
              </x-slot:trigger>
        </x-confirm-accept>
    </div>
</div>
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="font-bold">
           {{ $template->nama }} | {{$pengajuan->mahasiswa->nrp}} - {{$pengajuan->mahasiswa->name}}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <div class="w-full h-[80vh] border rounded-xl overflow-hidden">
            <iframe
                src="{{ route('pengajuan.preview.final', $pengajuan) }}"
                class="w-full h-full"
                style="border: none;"
            ></iframe>
        </div>
    </div>
</div>

@endsection
