<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function import(Request $request){

        $this->authorize('create', Dosen::class);

        $request->validate([
           'file' => 'required|mimes:xls,xlsx, csv'
        ]);

        try {
            $row = Excel::toArray([], $request->file('file'));

            $data = collect($row[0])
                ->skip(1)
                ->map(function ($row) {
                    return [
                        'nik' => $row[0] ?? null,
                        'nama' => $row[1] ?? null,
                    ];
                })
                ->filter(fn($row) => $row['nik'] && $row['nama'])
                ->values()
                ->toArray();

            return redirect()->back()
                ->with('preview_dosen', $data);

        } catch (\Throwable $e) {
            return redirect()
                ->route('dosen.index')
                ->with('error', $e->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data =  json_decode($request->data, true);

        foreach ($data as $row) {

             $exists = Dosen::where('nik', $row['nik'])->exists();

            if ($exists) {
                return back()->with(
                    'error', "Import Gagal : Dosen dengan NIK '{$row['nik']}' sudah ada pada sistem ini."
                );
            }

            $programStudiId = auth()->user()->program_studi_id;

            Dosen::create([
               'nama' => $row['nama'],
               'nik' => $row['nik'],
               'program_studi_id' => $programStudiId,
            ]);
        }

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
            'nik' => 'required|unique:dosens,nik,' . $dosen->id,
        ]);

        $dosen->update($data);
        return redirect()->route('dosen.index')
            ->with('success', 'Data Dosen berhasil di update');
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
