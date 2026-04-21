<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (Request $request) {
    $username = $request->username;
    $password = $request->password;

    if ($username === 'STF001' && $password === 'password') {
        return response()->json([
            'status' => 'success',
            'token' => 'dummy_token_123456',
            'user' => [
                'username' => $username,
                'name' => 'Staff Akademik',
            ]
        ]);
    }

    if ($username === 'STF002' && $password === 'password') {
        return response()->json([
            'status' => 'success',
            'token' => 'dummy_token_123456',
            'user' => [
                'username' => $username,
                'name' => 'Staff Akademik',
            ]
        ]);
    }

    if ($username === '2272001' && $password === 'password') {
        return response()->json([
            'status' => 'success',
            'token' => 'dummy_token_123456',
            'user' => [
                'username' => $username,
            ]
        ]);
    }

    if ($username === '2272002' && $password === 'password') {
        return response()->json([
            'status' => 'success',
            'token' => 'dummy_token_123456',
            'user' => [
                'username' => $username,
            ]
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized'
    ], 401);
});
