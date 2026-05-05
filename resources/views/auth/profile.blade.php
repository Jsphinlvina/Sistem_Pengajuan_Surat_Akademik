@extends('starter')
@section('title', 'Profile')

@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
  <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
      Profile
    </h3>
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
      <form action="{{ route('profile.update') }} " method="POST">
      @csrf
      @method('PUT')
        <div>
          <div class="grid grid-flow-col grid-rows-1 gap-6">
            <div>
              @if($user->nrp != null)
              <x-form-input name="nrp" label="NRP" :value="$user?->nrp" placeholder="2272001" readonly />
              @else
              <x-form-input name="kode" label="NIK" :value="$user?->kode" placeholder="2272001" readonly />
              @endif
            </div>
            <div>
              <x-form-input name="name" label="Nama Lengkap" :value="$user?->name" placeholder="John Doe" required />
            </div>

            <div>
              <x-form-input name="email" label="Email" type="email" :value="$user?->email"
                placeholder="john.doe@example.com" required />
            </div>
          </div>
          <div class="mt-6">
          </div>
          @if($user->nrp != null)
          <div class="mt-6">
            <x-form-input name="alamat" label="Alamat" :value="$user?->alamat" placeholder="Jl. Example No. 123"
              required />
          </div>
          @endif
          <button type="submit"
            class="px-4 py-3 mt-6 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600 flex">
            Update Profile
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
