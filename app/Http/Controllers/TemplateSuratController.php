<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templateSurat = TemplateSurat::all();
        return view('template-surat.index', compact('templateSurat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('template-surat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
           'nama' => 'required|unique:template_surats|max:255',
           'kode'=> 'required|unique:template_surats|max:50',
           'deskripsi' => 'required|string|max:255',
           'xml_content'=> 'required|mimes:xml',
           'dynamic_fields' => 'required',
            'status' => 'required|boolean',
        ]);

        TemplateSurat::create($data);
        return redirect()->route('template-surat.index')->with('success', 'Data Template Surat Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(TemplateSurat $templateSurat)
    {
        return view('templateSurat.show', compact('templateSurat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemplateSurat $templateSurat)
    {
        return view('template-surat.edit', compact('templateSurat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TemplateSurat $templateSurat)
    {
        $data = $request->validate([
           'nama' => 'required|max:255|unique:template_surats,nama' . $templateSurat->id,
           'kode'=> 'required|max:50|unique:template_surats,kode,' . $templateSurat->id,
           'deskripsi' => 'required|string|max:255',
           'xml_content'=> 'required|mimes:xml',
           'dynamic_fields' => 'required',
            'status' => 'required|boolean',
        ]);

        $templateSurat->update($data);
        return redirect()->route('template-surat.index')->with('success', 'Data Template Surat Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemplateSurat $templateSurat)
    {
        $templateSurat->delete();
        return redirect()->route('template-surat.index')->with('success', 'Data Template Surat Berhasil Dihapus');
    }
}
