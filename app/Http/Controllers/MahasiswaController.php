<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function __construct(){
        $this->authorizeResource(Mahasiswa::class, 'mahasiswa');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('statusPeriodeAktif')->get();
        return view('pages.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.mahasiswa.create');
    }

    public function importMahasiswa()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data =$request->validate([
           'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mahasiswas',
            'nrp'=> 'required|string|max:7',
        ]);

        $data['program_studi_id'] = auth()->user()->program_studi_id;

        Mahasiswa::create($data);

        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('programStudi');
        return view('pages.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $programStudis = ProgramStudi::all();
        return view('pages.mahasiswa.edit', compact('mahasiswa', 'programStudis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
         $data =$request->validate([
           'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mahasiswas,email,'. $mahasiswa->id,
            'nrp'=> 'required|string|max:7|unique:mahasiswas,nrp,'. $mahasiswa->id,
            'program_studi_id'=> 'required|exists:program_studi,id',
        ]);

         $mahasiswa->update($data);
         return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('mahasiswa.index')->with('success', 'Data Mahasiswa berhasil dihapus');
    }
}
