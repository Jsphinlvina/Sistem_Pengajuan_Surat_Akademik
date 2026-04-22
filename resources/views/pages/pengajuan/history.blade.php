@extends('starter')
@section('title', 'History Pengajuan Surat')
@section('content')
<x-table>
   {{-- Title Table --}}
   <x-slot:title>
      <div class="justify-between flex items-center">
         <p>History Pengajuan Surat</p>
      </div>
   </x-slot:title>
   {{-- End Title Table --}}

   {{-- Head Table --}}
   <x-slot:head>
      <tr class="border-b border-gray-100 dark:border-gray-800">
         @foreach(['No','Pengajuan Surat',' Tanggal Pengajuan', 'Status', 'Action'] as $column)
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
                  {{$pengajuan->templateSurat->nama}}
               </p>
            </div>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-gray-500 text-theme-sm ">
                  {{$pengajuan->created_at->format('d F Y')}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            <div class="flex items-center">
               <p class="text-white p-1 rounded text-theme-sm {{$pengajuan->status_color}} ">
                  {{$pengajuan->status_label}}
               </p>
            </div>
         </td>
         <td class="px-5 py-4 sm:px-6">
            @if($pengajuan->status === 1)
            <div class="flex items-center">
               <a href="{{ route('pengajuan.edit', $pengajuan) }}">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     class="stroke-gray-500 me-4" stroke="currentColor" width="20" height="20">
                     <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>
               </a>
               <x-comfirm-delete :action="route('pengajuan.destroy', $pengajuan->id)"
                  message="Yakin ingin membatalkan Pengajuan ini?">
                  <x-slot:trigger>
                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" width="17" height="17" class="stroke-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                           d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                     </svg>
                  </x-slot:trigger>
               </x-comfirm-delete>
            </div>
            @else
            <a href="{{ route('pengajuan.show', $pengajuan) }}">
               <svg width="20" height="20" class="stroke-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                     d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
               </svg>
            </a>
            @endif
         </td>
      </tr>
      @endforeach
   </x-slot>
   {{-- End Item Table--}}
</x-table>
@endsection
