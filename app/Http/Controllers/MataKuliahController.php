<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matakuliahs = MataKuliah::with('kurikulum')->get();
        return view('mata-kuliah.index', compact('matakuliahs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kurikululms = Kurikulum::with('programStudi')->get();
        return view('mata-kuliah.create', compact('kurikululms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:50|unique:mata_kuliahs,kode',
            'nama' => 'required|string|max:255|unique:mata_kuliahs,nama',
            'kurikulum_id' => 'required|exists:kurikulums,id',
        ]);

        MataKuliah::create($data);
        return redirect()->route('mata-kuliah.index')->with('success', 'Data Mata Kuliah Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('kurikulum');
        return view('mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        return view('pages.mata-kuliah.edit',compact('mataKuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:50|unique:mata_kuliahs,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:255|unique:mata_kuliahs,nama,' . $mataKuliah->id,
        ]);
        $mataKuliah->update($data);
        return redirect()->route('kurikulum.show', $mataKuliah->kurikulum_id)->with('success', 'Data Mata Kuliah Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        $kurikulumId = $mataKuliah->kurikulum_id;
        $deleted = $mataKuliah->smartDelete(['pengajuan']);

        return redirect()
            ->route('kurikulum.show', $kurikulumId)
            ->with(
                $deleted ? 'success' : 'error',
                $deleted ? 'Mata kuliah berhasil dihapus' : 'Mata kuliah tidak dapat dihapus karena data sudah digunakan'
            );
    }
}
