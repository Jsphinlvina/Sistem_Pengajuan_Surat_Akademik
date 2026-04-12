<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use App\Models\PeriodeSemester;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templateSurats = TemplateSurat::all();
        return view('pages.pengajuan.index', compact('templateSurats'));
    }

    public function redirectHalamanPengajuan(Request $request){
        $request->validate([
            'template_surat_id' => 'required|exists:template_surats,id',
        ]);

        $template = TemplateSurat::findOrFail($request->template_surat_id);
        $dynamicFields = $template->dynamicFields;

        return view('pages.pengajuan.form-surat', compact('template', 'dynamicFields'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodeSemesters = PeriodeSemester::all();
        $mahasiswas = Mahasiswa::with('programStudi')->get();
        $templateSurats = TemplateSurat::all();

        return view('pengajuan.create', compact('periodeSemesters', 'mahasiswas', 'templateSurats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'periode_semester_id' => 'required|exists:periode_semesters,id',
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'template_surat_id' => 'required|exists:template_surats,id',
            'status' => 'required|in:dalam_pengajuan',
        ]);

        Pengajuan::create($data);
        return redirect()->route('pengajuan.index')->with('success', 'Data Pengajuan Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('periodeSemester', 'mahasiswa', 'templateSurat', 'user');
        return view('pengajuan.show', compact('pengajuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengajuan $pengajuan)
    {
        return view('pengajuan.edit', compact('pengajuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Data Pengajuan Berhasil Dihapus');
    }
}
