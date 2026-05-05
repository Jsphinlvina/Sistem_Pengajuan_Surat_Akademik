<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(){
        $pengajuans = Pengajuan::byProgramStudi(auth()->user()->program_studi_id)
            ->where('created_at', '>=', now()->subWeek())
            ->where('status', '=', 1)
            ->get();

        return view('pages.dashboard.staff', compact('pengajuans'));
    }
}
