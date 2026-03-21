@php
$mahasiswa = $mahasiswa ?? null;
$mode = $mode ?? 'create';

$isShow = $mode === 'show';
$isEdit = $mode === 'edit';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $mode === 'create' ? 'Tambah Mahasiswa' : ($mode === 'edit' ? 'Edit Mahasiswa' : 'Detail Mahasiswa') }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">

        <form action="{{
                $mode === 'create'
                    ? route('mahasiswa.store')
                    : ($mode === 'edit' ? route('mahasiswa.update', $mahasiswa) : '#')
            }}" method="POST">
            @csrf
            @if($isEdit)
            @method('PUT')
            @endif

            <div class="grid grid-cols-2 gap-6 mb-6">
                <x-form-input name="kode" label="Kode User" :value="$mahasiswa?->nrp" :readonly="$isShow"
                    placeholder="720001" />

                <x-form-input name="name" label="Nama User" :value="$mahasiswa?->nama" :readonly="$isShow"
                    placeholder="John Doe" />
            </div>

            <div class="mb-6">
                <x-form-input name="email" label="Email" type="email" :value="$mahasiswa?->email"
                    :readonly="$isShow || $isEdit" placeholder="info@gmail.com" />
            </div>

            @if(!$isShow)
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <div class="mb-6">
                        <x-form-input type="password" name="password" label="Password"
                            placeholder="Minimal 8 karakter" />
                    </div>

                    <x-form-input type="password" name="password_confirmation" label="Confirm Password"
                        placeholder="Masukkan kembali password" />
                </div>

                <div>
                    <x-form-select name="program_studi_id" label="Program Studi" :value="$mahasiswa?->program_studi_id"
                        :options="$programStudis->pluck('nama', 'id')" :disabled="$isShow" />
                </div>
            </div>
            @endif

            <div class="mt-10">
                @if($mode === 'create')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Tambahkan Mahasiswa
                </button>

                @elseif($mode === 'edit')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update Mahasiswa
                </button>

                @else
                <a href="{{ route('mahasiswa.edit', $mahasiswa) }}"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Edit Mahasiswa
                </a>

                <form action="{{ route('mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        onclick="return confirm('Yakin ingin menghapus Mahasiswa ini?')"
                        class="ms-4 px-4 py-3 text-sm font-medium text-white rounded-lg bg-error-500 hover:bg-error-600">
                        Hapus Mahasiswa
                    </button>
                </form>
                @endif
            </div>

        </form>
    </div>
</div>
