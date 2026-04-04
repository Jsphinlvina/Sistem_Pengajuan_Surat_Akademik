<?php

namespace App\Http\Controllers;

use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TemplateSurat::class, 'template_surat');
    }

    private function extractDynamicFields($content)
    {
        preg_match_all('/{{\s*([a-zA-Z0-9_]+)\s*}}/', $content, $matches);

        return collect($matches[1])
            ->unique()
            ->values()
            ->toArray();
    }

    private function renderTemplate($content, $data)
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{'.$key.'}}', $value, $content);
        }

        return $content;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templateSurat = TemplateSurat::all();
        return view('pages.template-surat.index', compact('templateSurat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.template-surat.create');
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
            'xml_content'=> 'required',
        ]);

        $data['status'] = 1;
        $data['kode'] = strtoupper($data['kode']);
        $data['dynamic_fields'] = $this->extractDynamicFields($data['xml_content']);
        $data['program_studi_id'] = auth()->user()->program_studi_id;

        TemplateSurat::create($data);
        return redirect()
            ->route('template-surat.index')
            ->with('success', 'Data Template Surat Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(TemplateSurat $templateSurat)
    {
        //
    }

    public function preview(TemplateSurat $templateSurat)
    {
        $this->authorize('show', $templateSurat);

        $data = [
            'nama_mahasiswa' => 'Budi Santoso',
            'nrp' => '2272001',
            'program_studi' => 'Teknik Informatika',
            'tanggal' => now()->format('d F Y')
        ];

        $content = $this->renderTemplate($templateSurat->xml_content, $data);

        $pdf = Pdf::loadView('pdf.template-surat', [
            'content' => $content
        ]);

        return $pdf->stream('preview-surat.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemplateSurat $templateSurat)
    {
        return view('pages.template-surat.edit', compact('templateSurat'));
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
           'xml_content'=> 'required',
            'status' => 'required|boolean',
        ]);

        $data['kode'] = strtoupper($data['kode']);
        $data['dynamic_fields'] =  $this->extractDynamicFields($data['xml_content']);

        $templateSurat->update($data);
        return redirect()
            ->route('template-surat.index')
            ->with('success', 'Data Template Surat Berhasil Diubah');
    }

     public function updateStatus(Request $request, TemplateSurat $templateSurat)
     {
        $this->authorize('update', $templateSurat);

        $request->validate([
            'status' => 'required|integer'
        ]);

        $templateSurat->update($request->only('status'));
        return back()->with('success', 'Status Mahasiswa berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemplateSurat $templateSurat)
    {
        $deleted = $templateSurat->smartDelete(['pengajuan']);
        return redirect()->route('template-surat.index')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Template Surat berhasil dihapus permanen' : 'Template Surat tidak dapat dihapus karena sudah digunakan'
        );
    }
}
