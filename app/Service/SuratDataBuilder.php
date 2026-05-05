<?php

namespace App\Service;

use App\Models\Pengajuan;
use App\Models\TemplateSurat;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class SuratDataBuilder
{

   public static function bulanRomawi(int $bulan): string
    {
        return match ($bulan) {
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        };
    }

    private function generateNomorUrut(TemplateSurat $template): string
    {
        $count = Pengajuan::where('template_surat_id', $template->id)
            ->where('status', Pengajuan::status_disetujui)
            ->whereYear('updated_at', now()->year)
            ->count();

        return str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    public function generateNomorSuratFinal(Pengajuan $pengajuan): string
    {
        $template = $pengajuan->templateSurat;

        $nomorUrut = $this->generateNomorUrut($template);
        $bulanRomawi = self::bulanRomawi(now()->month);
        $tahun = now()->year;

        return "{$nomorUrut}/KAPROG/S1IF/{$template->kode}/{$bulanRomawi}/{$tahun}";
    }

    public static function generateQrImage(Pengajuan $pengajuan): string
    {
        $qr = QrCode::create(route('surat.verify', $pengajuan->id))
                ->setSize(200);

        $writer = new PngWriter();
        $result = $writer->write($qr);

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }

    public static function fromPengajuan(Pengajuan $pengajuan): array
    {
        $template = $pengajuan->templateSurat;
        $periode = $pengajuan->periodeSemester;
        $mahasiswa = $pengajuan->mahasiswa;

        $defaultData = collect($template->dynamic_fields ?? [])
            ->mapWithKeys(fn($field) => [$field => ''])
            ->toArray();

        $systemData = [
            'kode_surat' => 'XXXX/KAPROG/S1IF/'
                . $template->kode . '/'
                . self::bulanRomawi(now()->month) . '/'
                . now()->year,

            'nama_mahasiswa' => $mahasiswa->name,
            'nrp_mahasiswa' => $mahasiswa->nrp,
            'email_mahasiswa' => $mahasiswa->email,
            'alamat_mahasiswa' => $mahasiswa->alamat,

            'prodi_mahasiswa' => $mahasiswa->programStudi->nama ?? '',
            'periode_semester' => $periode->nama,

            'tanggal_surat' => now()->translatedFormat('d F Y'),

            'nama_kaprodi' => $periode->dosen->nama,
            'nik_kaprodi' => $periode->dosen->nik
        ];

         if ($template->id == 2){
                 $pengaju = [
                    'nrp'  => $mahasiswa->nrp,
                    'nama' => $mahasiswa->name,
                ];

                $mahasiswaTambahan = collect($pengajuan->data_pengajuan['mahasiswa'] ?? [])
                    ->filter(fn($m) => !empty($m['nrp']) && !empty($m['nama']));

               if ($mahasiswaTambahan->isNotEmpty()) {
                    $mahasiswaTable = collect([$pengaju])
                        ->merge($mahasiswaTambahan)
                        ->unique('nrp')
                        ->values();
                } else {
                    $mahasiswaTable = collect([$pengaju]);
                }

                if ($pengajuan->mataKuliah) {
                    $systemData['kode_mata_kuliah'] = $pengajuan->mataKuliah->kode;
                    $systemData['nama_mata_kuliah'] = $pengajuan->mataKuliah->nama;
                }

                $systemData['tableMahasiswa'] = view(
                    'components.table-mahasiswa',
                    ['mahasiswa' => $mahasiswaTable]
                )->render();


        } elseif ($template->id == 3){
             $systemData['tanggal_lulus'] = Carbon::parse($mahasiswa->tahun_lulus)->translatedFormat('d F Y');
         }

        return array_merge(
            $defaultData,
            $systemData,
            $pengajuan->data_pengajuan ?? []
        );
    }
}
