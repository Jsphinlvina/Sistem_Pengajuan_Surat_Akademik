<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programStudis = ProgramStudi::all();
        return view('pages.program-studi.index', compact('programStudis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.program-studi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|unique:program_studis',
            'nama' => 'required|unique:program_studis',
        ]);

        ProgramStudi::create($data);
        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi Berhahsil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramStudi $programStudi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramStudi $programStudi)
    {
        return view('pages.program-studi.edit', compact('programStudi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramStudi $programStudi)
    {
        $data = $request->validate([
           'kode' => 'required|unique:program_studis,kode,' . $programStudi->id,
           'nama' => 'required|unique:program_studis,nama,' . $programStudi->id
        ]);

        $programStudi->update($data);
        return redirect()->route('program-studi.index')->with('success', 'Data Program Studi Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramStudi $programStudi)
    {
        $deleted = $programStudi->smartDelete(['kurikulum', 'user', 'mahasiswa']);

        return redirect()->route('program-studi.index')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Program Studi berhasil dihapus permanen' : 'Program Studi tidak dapat dihapus karena data sudah digunakan'
        );
    }

    public function updateStatus(Request $request, ProgramStudi $programStudi)
    {
        $programStudi->status = $request->status;
        $programStudi->save();

        return back();
    }
}
