@extends('starter')
@section('title', 'Dashboard Admin')
@section('content')
<x-table>
  {{-- Title Table --}}
  <x-slot:title>
    <p>Welcome Admin!</p>
  </x-slot:title>
  {{-- End Title Table --}}

  {{-- Head Table --}}
  <x-slot:head>
    <tr class="border-b border-gray-100 dark:border-gray-800">
       @foreach(['No','Nama User','Program Studi','Role'] as $column)
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
    @foreach($users as $user)
    <tr class="odd:bg-gray-100">
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
            {{$user->name}}
          </p>
        </div>
      <td class="px-5 py-4 sm:px-6">
        <div class="flex items-center">
          <p class="text-gray-500 text-theme-sm dark:text-gray-400">
            {{$user->programStudi?->nama ?? '-'}}
          </p>
        </div>
      </td>
      <td class="px-5 py-4 sm:px-6">
        <div class="flex items-center">
          <p class="text-gray-500 text-theme-sm dark:text-gray-400">
            {{$user->role == 0 ? 'Admin' : 'Staff'}}
          </p>
        </div>
      </td>
    </tr>
    @endforeach
  </x-slot>
  {{-- End Item Table--}}
</x-table>
@endsection
