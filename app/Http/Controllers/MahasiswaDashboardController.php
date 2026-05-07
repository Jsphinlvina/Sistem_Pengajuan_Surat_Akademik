<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
         $pengajuans = Pengajuan::where('mahasiswa_id', auth()->user()->id)
            ->where('updated_at', '>=', now()->subWeek())
            ->where('status', '!=', 4)
            ->orderByDesc('updated_at')
            ->get();

        return view('pages.dashboard.mahasiswa', compact('pengajuans'));
    }
}
