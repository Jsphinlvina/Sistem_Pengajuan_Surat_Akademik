<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.mahasiswa');
    }
}
