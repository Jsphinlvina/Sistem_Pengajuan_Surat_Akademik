@extends('starter')
@section('title', 'Pengajuan Surat')
@section('content')
<x-table>
   {{-- Title Table --}}
   <x-slot:title>
      <div class="justify-between flex items-center">
         <p>Pengajuan Surat</p>
      </div>
   </x-slot:title>
   {{-- End Title Table --}}

   {{-- Head Table --}}
   <x-slot:head>
      <tr class="border-b border-gray-100 dark:border-gray-800">
         @foreach(['No', 'Periode Semester', 'Nama Mahasiswa','Pengajuan Surat',' Tanggal Pengajuan', 'Action'] as $column)
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
   {{-- End Head Table --}}

   {{-- Item Table--}}
   <x-slot>
      @foreach($pengajuans as $pengajuan)
      <tr class=" odd:bg-gray-100">
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                  {{$loop->iteration}}.
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm ">
                  {{$pengajuan->periodeSemester->nama}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm ">
                  {{$pengajuan->mahasiswa->nrp}} - {{$pengajuan->mahasiswa->name}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm ">
                  {{$pengajuan->templateSurat->nama}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm ">
                  {{$pengajuan->created_at->format('d F Y')}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <a class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-emerald-500 shadow-theme-xs hover:bg-emerald-600"
                href="{{ route('pengajuan.proses.detail', $pengajuan->id) }}">
                Cek Pengajuan
             </a>
            </div>
         </td>
      </tr>
      @endforeach
   </x-slot>
   {{-- End Item Table--}}
</x-table>
@endsection
