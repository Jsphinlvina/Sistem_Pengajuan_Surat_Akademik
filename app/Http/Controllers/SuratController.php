<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\SuratDataBuilder;
use App\Models\Pengajuan;

class SuratController extends Controller
{
   public function verify(Pengajuan $pengajuan)
    {
        abort_if($pengajuan->status !== Pengajuan::status_disetujui, 404);

        return view('pages.surat.verifikasi', [
            'pengajuan' => $pengajuan,
            'kodeSurat' => $pengajuan->data_pengajuan['kode_surat'] ?? '-'
        ]);
    }
}
