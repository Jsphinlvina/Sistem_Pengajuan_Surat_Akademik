<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\PeriodeSemester;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    /**
     * Halaman pilih template surat
     */
    public function index()
    {
        $templateSurats = TemplateSurat::where('status',1)->get();

        return view('pages.pengajuan.index', compact('templateSurats'));
    }

    /**
     * Redirect ke form pengajuan berdasarkan template
     */
   public function redirectHalamanPengajuan(Request $request)
    {
        $request->validate([
            'template_surat_id' => 'required|exists:template_surats,id',
        ]);

        $template = TemplateSurat::findOrFail($request->template_surat_id);

        $dynamicFields = $template->dynamic_fields ?? [];

        $user = auth()->user();

        $showFields = [
            'nama_mahasiswa' => $user->nama,
            'email_mahasiswa' => $user->email,
            'nrp_mahasiswa' => $user->nrp,
            'alamat_mahasiswa' => $user->alamat,
        ];

        $systemFieldKeys = [
            'nama_kaprodi',
            'nik_kaprodi',
            'periode_semester',
            'kode_surat',
            'tanggal_surat',
            'prodi_mahasiswa',
            'tanggal_lulus'
        ];

        $showFieldKeys = array_keys($showFields);

        $excludeFields = array_merge($showFieldKeys, $systemFieldKeys);

        $formFields = array_values(
            array_diff($dynamicFields, $excludeFields)
        );

        return view(
            'pages.pengajuan.create',
            compact(
                'template',
                'formFields',
                'showFields'
            )
        );
    }

    /**
     * Simpan pengajuan
     */
    public function store(Request $request)
    {
        $request->validate([
            'template_surat_id' => 'required|exists:template_surats,id',
            'fields' => 'nullable|array'
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        $periodeSemester = PeriodeSemester::where('status',1)->first();

        Pengajuan::create([

            'template_surat_id' => $request->template_surat_id,

            'mahasiswa_id' => $mahasiswa->id,

            'periode_semester_id' => $periodeSemester->id,

            'status' => Pengajuan::status_dalam_pengajuan,

            'user_id' => auth()->id(),

            'data_pengajuan' => $request->fields

        ]);

        return redirect()
            ->route('pengajuan.index')
            ->with('success','Pengajuan surat berhasil dibuat');
    }

    /**
     * Detail pengajuan
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(
            'mahasiswa',
            'templateSurat',
            'periodeSemester',
            'user'
        );

        return view(
            'pages.pengajuan.show',
            compact('pengajuan')
        );
    }

    /**
     * Hapus pengajuan
     */
    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();

        return redirect()
            ->route('pengajuan.index')
            ->with('success','Pengajuan berhasil dihapus');
    }
}
