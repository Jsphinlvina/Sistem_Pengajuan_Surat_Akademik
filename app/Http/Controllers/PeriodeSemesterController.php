<?php

namespace App\Http\Controllers;

use App\Models\PeriodeSemester;
use Illuminate\Http\Request;

class PeriodeSemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodeSemesters = PeriodeSemester::all();
        return view('periode-semester.index', compact('periodeSemesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('periode-semester.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|unique:periode_semesters|max:255',
            'status' =>'required|boolean',
            'kaprodi'=> 'required|string|max:255'
        ]);

        PeriodeSemester::create($data);
        return redirect()->route('periode-semester.index')->with('success', 'Data Periode Semester Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodeSemester $periodeSemester)
    {
        return view('periode-semester.show', compact('periodeSemester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeSemester $periodeSemester)
    {
        return view('periode-semester.edit', compact('periodeSemester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodeSemester $periodeSemester)
    {
        $data = $request->validate([
            'nama' => 'required|max:255|unique:periode_semesters,nama,' . $periodeSemester->id,
            'status' =>'required|boolean',
            'kaprodi'=> 'required|string|max:255'
        ]);

        $periodeSemester->update($data);
        return redirect()->route('periode-semester.index')->with('success', 'Data Periode Semester Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeSemester $periodeSemester)
    {
        $periodeSemester->delete();
        return redirect()->route('periode-semester.index')->with('success', 'Data Periode Semester Berhasil Dihapus');
    }
}
