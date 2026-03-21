@extends('starter')
@section('title', 'Data Mahasiswa')
@section('content')
<x-table>
   {{-- Title Table --}}
   <x-slot:title>
      <div class="justify-between flex items-center">
         <p>Data Mahasiswa</p>
         <a
            class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-emerald-500 shadow-theme-xs hover:bg-emerald-600" href="{{ route('mahasiswa.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
               stroke="currentColor" class="w-6 h-5 text-white">
               <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Tambah Mahasiswa
         </a>
      </div>
   </x-slot:title>
   {{-- End Title Table --}}

   {{-- Head Table --}}
   <x-slot:head>
      <tr class="border-b border-gray-100 dark:border-gray-800">
          @foreach(['No','NRP','Nama Mahasiswa','Status'] as $column)
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
      @foreach($mahasiswas as $mahasiswa)
      <tr class="cursor-pointer hover:bg-gray-100" onclick="window.location='{{ route('mahasiswa.show', $mahasiswa) }}'">
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
                  {{$mahasiswa->nrp}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                  {{$mahasiswa->nama}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                  {{$mahasiswa->mahasiswaPeriodePengajuan?->status ?? '-'}}
               </p>
            </div>
         </td>
      </tr>
      @endforeach
   </x-slot>
   {{-- End Item Table--}}
</x-table>
@endsection
