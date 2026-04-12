@extends('starter')
@section('title', 'Data User')
@section('content')
<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
        <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            Jenis Pengajuan Surat
        </h3>
    </div>
     <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
         <form action="{{ route('pengajuan.redirect') }}" method="POST">
            @csrf
            <x-form-select name="template_surat_id" :options="$templateSurats->pluck('nama', 'id')" />
            <div class="mt-5">
                 <button type="submit"
                        class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                        Next
                </button>
            </div>
         </form>
     </div>
</div>
@endsection
