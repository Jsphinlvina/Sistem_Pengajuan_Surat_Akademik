<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaPeriodeSemester;
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

        $user = auth()->user();

         $isAktif = MahasiswaPeriodeSemester::where('mahasiswa_id', $user->id)
            ->where('status', 1)
            ->whereHas('periodeSemester', fn ($q) => $q->active())
            ->exists();


        if (! $isAktif) {
            return redirect()
                ->back()
                ->with('error', 'Anda tidak aktif pada periode semester saat ini.');
        }

        $periode_semester = PeriodeSemester::active()->firstOrFail();

        $template = TemplateSurat::findOrFail($request->template_surat_id);

        $dynamicFields = $template->dynamic_fields ?? [];

        $showFields = [
            'nama_mahasiswa' => $user->nama,
            'email_mahasiswa' => $user->email,
            'nrp_mahasiswa' => $user->nrp,
            'alamat_mahasiswa' => $user->alamat,
            'periode_semester' => $periode_semester->nama
        ];

        $systemFieldKeys = [
            'nama_kaprodi',
            'nik_kaprodi',
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
