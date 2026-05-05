<x-guest-layout>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6 flex items-center">
  <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
    <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">
      Profile
    </h3>
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
      <form action="{{ route('mahasiswa.first-login.store') }}" method="POST">
      @csrf
        <div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
              <x-form-input name="nrp" label="NRP" :value="$mahasiswa?->nrp" placeholder="2272001" readonly />
            </div>
            <div>
              <x-form-input name="name" label="Nama Lengkap" :value="$mahasiswa?->name" placeholder="John Doe" required />
            </div>

            <div>
              <x-form-input name="email" label="Email" type="email" :value="$mahasiswa?->email"
                placeholder="john.doe@example.com" required />
            </div>
          </div>
          <div class="mt-6">
          </div>
          <div class="mt-6">
            <x-form-input name="alamat" label="Alamat" :value="$mahasiswa?->alamat" placeholder="Jl. Example No. 123"
              required />
          </div>
          <button type="submit"
            class="px-4 py-3 mt-6 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600 flex">
            Update Profile
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</x-guest-layout>
