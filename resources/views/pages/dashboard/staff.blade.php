@extends('starter')
@section('title', 'Dashboard Staff')
@section('content')
<x-table>
  {{-- Title Table --}}
  <x-slot:title>
    <p>Welcome {{ auth()->user()->name }}!</p>
  </x-slot:title>
  {{-- End Title Table --}}

  {{-- Head Table --}}
  <x-slot:head>
    <tr class="border-b border-gray-100 dark:border-gray-800">
       @foreach(['Nama Pengajuan','Nama Mahasiswa', 'Tanggal Pengajuan', 'Status'] as $column)
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
    <tr class="odd:bg-gray-100">
      <td class="px-5 py-4 sm:px-6">
        <div class="flex items-center">
          <p class="text-gray-500 text-theme-sm ">
            {{$pengajuan->templateSurat->nama}}
          </p>
        </div>
      <td class="px-5 py-4 sm:px-6">
        <div class="flex items-center">
          <p class="text-gray-500 text-theme-sm ">
            {{$pengajuan->mahasiswa?->name ?? '-'}}
          </p>
        </div>
      </td>
      <td class="px-5 py-4 sm:px-6">
        <div class="flex items-center">
          <p class="text-gray-500 text-theme-sm ">
            {{ $pengajuan->created_at->format('d F Y')}}
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
    </tr>
    @endforeach
  </x-slot>
  {{-- End Item Table--}}
</x-table>
@endsection
