@php
$user = $user ?? null;
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
$isShow = $mode === 'show';

$template = $template ?? null;
$formFields = $formFields ?? [];
$showFields = $showFields ?? [];
@endphp

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
    <div class="px-5 py-4 sm:px-6 sm:py-5">
        <h3 class="font-bold">
            {{ $template->nama }}
        </h3>
    </div>

    <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800 px-10">
        <form action="{{ $isEdit ? route('pengajuan.update', $pengajuan->id) : route('pengajuan.store') }}" method="POST" >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <input type="hidden" name="template_surat_id" value="{{ $template->id }}">
            @foreach($showFields as $key => $value)
                <div class="mt-3">
                    <x-form-input
                        name="show[{{ $key }}]"
                        label="{{ ucwords(str_replace('_',' ',$key)) }}"
                        :value="$value"
                        readonly
                    />
                </div>
            @endforeach
            @if ($template->id == 2 && !$isShow)
            <div class="mt-3">
                <p class="mb-1.5 text-sm font-medium text-gray-700">Anggota Mahasiswa Lainnya</p>

                <div id="mahasiswa-wrapper">
                    @php
                        $listMahasiswa = old(
                            'fields.mahasiswa',
                            $pengajuan->data_pengajuan['mahasiswa'] ?? [[]]
                        );
                    @endphp

                    @foreach($listMahasiswa as $i => $m)
                        <div class="grid grid-cols-2 gap-3 mb-2">
                            <x-form-input
                                name="fields[mahasiswa][{{ $i }}][nrp]"
                                :value="$m['nrp'] ?? ''"
                                :readonly="$isShow"
                                placeholder="NRP"
                            />
                            <x-form-input
                                name="fields[mahasiswa][{{ $i }}][nama]"
                                :value="$m['nama'] ?? ''"
                                :readonly="$isShow"
                                placeholder="Nama Mahasiswa"
                            />
                        </div>
                    @endforeach
                </div>

                <button
                    type="button"
                    onclick="addMahasiswa()"
                    class="px-4 py-1 text-sm font-medium rounded-lg text-white bg-blue-500"
                >
                    + Tambah Mahasiswa
                </button>
            </div>
            @endif
            @if ($template->id == 2)
                <div class="mt-3">
                @if ($isShow)
                    <x-form-input
                        name="fields[kode_mata_kuliah]"
                        label="Mata Kuliah"
                        :value="$mataKuliahLabel ?? null"
                        readonly
                    />

                @else
                    <x-form-select
                        name="fields[kode_mata_kuliah]"
                        label="Mata Kuliah"
                        :options="$mataKuliahOptions"
                        :optionSelect="false"
                        :value="old('fields.kode_mata_kuliah', $pengajuan->data_pengajuan['kode_mata_kuliah'] ?? null)"
                        required
                    />
                @endif
                </div>
            @endif
            @foreach($formFields as $field)
                <div class="mt-3">
                    <x-form-input
                        name="fields[{{ $field }}]"
                        label="{{ ucwords(str_replace('_',' ',$field)) }}"
                        :value="old('fields.'.$field, $pengajuan->data_pengajuan[$field] ?? '')"
                        :readonly="$isShow"
                        required
                    />
                </div>
            @endforeach
            <div class="mt-10">
                @if(!$isShow)
                <button
                    type="submit"
                    class="px-4 py-3 text-sm font-medium text-white rounded-lg
                        {{ $isEdit ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-emerald-500 hover:bg-emerald-600' }}">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Ajukan Pengajuan' }}
                </button>
                @endif
            </div>
        </form>
    </div>
</div>


<script>
let index = {{ count($listMahasiswa ?? [0]) }};

function addMahasiswa() {
    const wrapper = document.getElementById('mahasiswa-wrapper');

    const row = document.createElement('div');
    row.className = 'grid grid-cols-2 gap-3 mb-2';

    row.innerHTML = `
        <x-form-input
            name="fields[mahasiswa][${index}][nrp]"
            :value="$m['nrp'] ?? ''"
            :readonly="$isShow"
            placeholder="NRP"
        />
        <x-form-input
            name="fields[mahasiswa][${index}][nama]"
            :value="$m['nama'] ?? ''"
            :readonly="$isShow"
            placeholder="Nama Mahasiswa"
        />
    `;

    wrapper.appendChild(row);
    index++;
}
</script>
