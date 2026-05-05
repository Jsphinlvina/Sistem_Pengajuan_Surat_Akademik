<x-guest-layout>
  <!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />
<div class="flex justify-center items-center">
    <div class="max-w-3xl mx-auto mt-10">
        <div class="rounded-2xl border border-green-300 bg-green-50 p-6 mb-6">
            <h1 class="text-xl font-bold text-green-700">
                Surat Terverifikasi
            </h1>
            <p class="text-sm text-green-700 mt-1">
                Dokumen ini telah diterbitkan secara resmi oleh sistem.
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-4">

            <div class="grid grid-cols-3 gap-4">
                <div class="text-gray-500">Nomor Surat</div>
                <div class="col-span-2 font-semibold text-gray-800">
                    {{ $kodeSurat }}
                </div>

                <div class="text-gray-500">Periode Semester</div>
                <div class="col-span-2 font-semibold text-gray-800">
                    {{ $pengajuan->periodeSemester->nama }}
                </div>

                <div class="text-gray-500">Nama Mahasiswa</div>
                <div class="col-span-2">
                    {{ $pengajuan->mahasiswa->name }}
                </div>

                <div class="text-gray-500">NRP</div>
                <div class="col-span-2">
                    {{ $pengajuan->mahasiswa->nrp }}
                </div>

                <div class="text-gray-500">Program Studi</div>
                <div class="col-span-2">
                    {{ $pengajuan->mahasiswa->programStudi->nama ?? '-' }}
                </div>

                <div class="text-gray-500">Jenis Surat</div>
                <div class="col-span-2">
                    {{ $pengajuan->templateSurat->nama }}
                </div>

                <div class="text-gray-500">Status</div>
                <div class="col-span-2">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                        {{ $pengajuan->status_label }}
                    </span>
                </div>

                <div class="text-gray-500">Disetujui Oleh</div>
                <div class="col-span-2">
                    {{ $pengajuan->user?->name ?? '-' }}
                </div>

                <div class="text-gray-500">Tanggal Persetujuan</div>
                <div class="col-span-2">
                    {{ $pengajuan->updated_at->translatedFormat('d F Y') }}
                </div>
            </div>
        </div>

        <div class="mt-6 text-sm text-gray-500 text-center">
            Surat ini diverifikasi melalui sistem pengajuan surat akademik.
            <br>
            Jika terdapat perbedaan isi, silakan hubungi pihak administrasi.
        </div>

    </div>
</div>
</x-guest-layout>
