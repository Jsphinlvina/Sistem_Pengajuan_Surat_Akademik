<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MahasiswaPeriodeSemester;
use App\Models\PeriodeSemester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaPeriodeSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswaPeriodeSemesters = MahasiswaPeriodeSemester::with('mahasiswa','periodeSemester');
        return view('mahasiswa-periode-semester.index', compact('mahasiswaPeriodeSemesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mahasiswas = Mahasiswa::with('programStudi')->get();
        $periodeSemesters = PeriodeSemester::all();

        return view('mahasiswa-periode-semester.create', compact('mahasiswas','periodeSemesters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'mahasiswa_id' => [
                'required',
                'exists:mahasiswas,id',
                Rule::unique('mahasiswa_periode_semester')
                    ->where('periode_semester_id', request('periode_semester_id')),
            ],
            'periode_semester_id' => 'required|exists:periode_semesters,id',
            'status' => 'required|in:aktif,cuti,lulus,drop_out',
            'deskripsi' => 'required|string|max:255',
        ]);

        MahasiswaPeriodeSemester::create($data);
        return redirect()->route('mahasiswa-periode-semester.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $mahasiswaPeriodeSemester->load('mahasiswa', 'periodeSemester');
        return view('mahasiswaPeriodeSemester.show', compact('mahasiswaPeriodeSemester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $mahasiswas = Mahasiswa::with('programStudi')->get();
        $periodeSemesters = PeriodeSemester::all();

        return view('mahasiswa-periode-semester.edit', compact('mahasiswaPeriodeSemester','mahasiswas','periodeSemesters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $data = $request->validate([
        'mahasiswa_id' => [
            'required',
            'exists:mahasiswas,id',
            Rule::unique('mahasiswa_periode_semester')
                ->where('periode_semester_id', request('periode_semester_id'))
                ->ignore($mahasiswaPeriodeSemester->id),
        ],
        'periode_semester_id' => 'required|exists:periode_semesters,id',
        'status' => 'required|in:aktif,cuti,lulus,drop_out',
        'deskripsi' => 'required|string|max:255',
        ]);

        $mahasiswaPeriodeSemester->update($data);
        return redirect()->route('mahasiswa-periode-semester.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $mahasiswaPeriodeSemester->delete();
        return redirect()->route('mahasiswa-periode-semester.index')->with('success', 'Data Periode Mahsiswa berhasil dihapus');
    }
}
