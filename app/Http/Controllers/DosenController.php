<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Dosen::class, 'dosen');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = Dosen::all();
        return view('pages.dosen.index', compact('dosens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
           'nama' => 'required|max:255',
            'nik' => 'required|unique:dosen',
        ]);

        $programStudiId = auth()->user()->program_studi_id;

        $dosen = Dosen::create([
           'nama' => $request->nama,
           'nik' => $request->nik,
           'program_studi_id' => $programStudiId,
        ]);

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data Dosen berhasil di tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dosen $dosen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        return view('pages.dosen.edit', compact('dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dosen $dosen)
    {
        $data = $request->validate([
            'nama' => 'required|max:255',
            'nik' => 'required|unique:dosen,nik,' . $dosen,
        ]);

        $dosen->update($data);
        return redirect()->route('dosen.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dosen $dosen)
    {
        $deleted = $dosen->smartDelete(['periodeSemester']);

        return redirect()
            ->route('dosen.index')
            ->with(
              $deleted ? 'success' : 'error',
              $deleted ? 'Dosen berhasil dihapus' : 'Dosen tidak dapat dihapus karena data sudah digunakan'
            );
    }
}
