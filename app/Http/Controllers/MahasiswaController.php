<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $mahasiswas = Mahasiswa::all();
        return view('pages.mahasiswa.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.mahasiswa.create');
    }

    public function import(Request $request)
    {
        $this->authorize('create', Mahasiswa::class);

        $request->validate([
           'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            $row = Excel::toArray([], $request->file('file'));

            $data = collect($row[0])
                ->skip(1)
                ->map(function ($row) {
                    return [
                        'nrp' => $row[0] ?? null,
                        'nama' => $row[1] ?? null,
                        'email' => $row[2] ?? null,
                        'alamat' => $row[3] ?? null,
                    ];
                })
                ->filter(fn($row) => $row['nrp'] && $row['nama'])
                ->values()
                ->toArray();

            return redirect()->back()
                ->with('preview_mahasiswa', $data);

        } catch (\Throwable $e) {
            return redirect()
                ->route('mahasiswa.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = json_decode($request->data, true);

       foreach ($data as $row){

           $exist = Mahasiswa::where('nrp', $row['nrp'])->exists();

           if($exist){
               return back()->with(
                       'error', "Import Gagal : Mahasiswa dengan NRP '{$row['nrp']}' sudah digunakan"
                   );
           }

           $programStudiId = auth()->user()->program_studi_id;

           Mahasiswa::create([
               'nrp' => $row['nrp'],
               'nama' => $row['nama'],
               'email' => $row['email'],
               'alamat' => $row['alamat'],
               'program_studi_id' => $programStudiId,
           ]);
       }

        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data Mahasiswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return view('pages.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('pages.mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
         $data =$request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:mahasiswas,email,'. $mahasiswa->id,
            'nrp' => 'required|string|max:7|unique:mahasiswas,nrp,'. $mahasiswa->id,
            'alamat' => 'required|string|max:255',
            'tahun_lulus' => 'nullable|date`'
        ]);

         $mahasiswa->update($data);
         return redirect()->route('mahasiswa.index')
             ->with('success', 'Data Mahasiswa berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->smartDelete(['pengajuan', 'mahasiswaPeriodeSemester']);
        return redirect()
            ->route('mahasiswa.index')
            ->with('success', 'Data Mahasiswa berhasil dihapus');
    }
}
