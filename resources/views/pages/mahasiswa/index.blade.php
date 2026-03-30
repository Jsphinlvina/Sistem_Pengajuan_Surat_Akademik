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
            Import Data Mahasiswa
         </a>
      </div>
   </x-slot:title>
   {{-- End Title Table --}}

   {{-- Head Table --}}
   <x-slot:head>
      <tr class="border-b border-gray-100 dark:border-gray-800">
          @foreach(['No','NRP','Nama Mahasiswa','Status', 'Action'] as $column)
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
          <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <a href="{{ route('mahasiswa.show', $mahasiswa) }}">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     class="stroke-gray-500 me-4" stroke="currentColor" width="20" height="20">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>
               </a>
            </div>
         </td>
      </tr>
      @endforeach
   </x-slot>
   {{-- End Item Table--}}
</x-table>
@endsection
