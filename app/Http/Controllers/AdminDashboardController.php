<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::where('updated_at', '>=', now()->subWeek())->get();
        return view('pages.dashboard.admin', compact('users'));
    }
}
