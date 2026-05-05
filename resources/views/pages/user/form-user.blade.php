@php
$user = $user ?? null;
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
            {{ $mode === 'create' ? 'Tambah User' : ($mode === 'edit' ? 'Edit User' : 'Detail User') }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">

        <form action="{{
                $mode === 'create'
                    ? route('user.store')
                    : ($mode === 'edit' ? route('user.update', $user) : '#')
            }}" method="POST">
            @csrf
            @if($isEdit)
            @method('PUT')
            @endif

            <div class="grid grid-cols-2 gap-6 mb-6">
                <x-form-input name="kode" label="Kode User" :value="$user?->kode" :readonly="$isEdit"
                    placeholder="720001" required />

                <x-form-input name="name" label="Nama User" :value="$user?->name" placeholder="John Doe" required maxlength="200"/>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="mb-6">
                    <x-form-input name="email" label="Email" type="email" :value="$user?->email"
                        placeholder="info@gmail.com" required />
                </div>
                <div>
                    <x-form-select name="program_studi_id" label="Program Studi" :value="$user?->program_studi_id"
                        :options="$programStudis->pluck('nama', 'id')" />
                </div>
            </div>
            <div class="mt-10">
                @if($mode === 'create')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-emerald-500 hover:bg-emerald-600">
                    Tambahkan User
                </button>
                @elseif($mode === 'edit')
                <button type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg bg-yellow-500 hover:bg-yellow-600">
                    Update User
                </button>
                @endif
            </div>
        </form>
    </div>
</div>
