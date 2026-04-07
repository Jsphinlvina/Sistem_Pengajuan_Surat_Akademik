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
       //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //
    }

    /**
     * Display the specified resource.
     */
    public function show(MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $mahasiswaPeriodeSemester->load('mahasiswa');
        return view('pages.mahasiswa-periode.edit', compact('mahasiswaPeriodeSemester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $data = $request->validate([
            'status' => 'required|integer',
        ]);

        $mahasiswaPeriodeSemester->update($data);
        return redirect()
            ->route('periode-semester.show', $mahasiswaPeriodeSemester->periode_semester_id)
            ->with('success', 'Data Mahasiswa berhasil diupdate');
    }

    public function updateStatus(Request $request, MahasiswaPeriodeSemester $mahasiswaPeriodeSemester){
        $request->validate([
            'status' => 'required|integer'
        ]);

        $mahasiswaPeriodeSemester->update($request->only('status'));
        return back()->with('success', 'Status Mahasiswa berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MahasiswaPeriodeSemester $mahasiswaPeriodeSemester)
    {
        $deleted = $mahasiswaPeriodeSemester->delete();

        return redirect()
            ->route('periode-semester.show', $mahasiswaPeriodeSemester->periode_semester_id)
            ->with('success', 'Data Periode Mahsiswa berhasil dihapus');
    }
}
