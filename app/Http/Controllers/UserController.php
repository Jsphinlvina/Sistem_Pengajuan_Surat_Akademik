<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller

{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('programStudi')->get();
        return view('pages.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programStudis = ProgramStudi::where('status', 1)->get();
        return view('pages.user.create', compact('programStudis'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|unique:users,kode',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'program_studi_id' => 'required|exists:program_studis,id'
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = 1;

        User::create($data);

        return redirect()->route('user.index')
            ->with('success', 'Data User Berhasil Dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $programStudis = ProgramStudi::all();
        return view('pages.user.edit', compact('user', 'programStudis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'kode' => 'required|unique:users,kode,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. $user->id,
            'password' => 'nullable|min:8|confirmed',
            'program_studi_id' => 'required|exists:program_studis,id'
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        $data['role'] = 1;

        $user->update($data);
        return redirect()
            ->route('user.index')
            ->with('success', 'Data User Berhasil Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $deleted = $user->smartDelete(['pengajuan']);

        return redirect()->route('user.index')->with(
            $deleted ? 'success' : 'info',
            $deleted ? 'User berhasil dihapus' : 'User tidak dapat dihapus karena sudah digunakan'
          );
    }

    public function updateStatus(Request $request, User $user)
    {
        $user->status = $request->status;
        $user->save();

        return back();
    }
}
