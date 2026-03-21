<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $user = auth()->user();

        if ($user->role == 0){
            return redirect()->route('admin.dashboard');
        }

        if ($user->role == 1){
            return redirect()->route('staff.dashboard');
        }
    }
}
