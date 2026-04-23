<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\MahasiswaPeriodeSemester;
use App\Models\MataKuliah;
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

        $mataKuliahOptions = [];

        if ($request->template_surat_id == 2) {

            $kurikulumAktif = Kurikulum::where('status', 1)->firstOrFail();

            $mataKuliahOptions = MataKuliah::where('kurikulum_id', $kurikulumAktif->id)
            ->orderBy('kode')
            ->get()
            ->mapWithKeys(function ($mk) {
                return [
                    $mk->kode => "{$mk->kode} - {$mk->nama}"
                ];
            });
        }

        if ($request->template_surat_id == 3) {
             $isLulus = Mahasiswa::where('id', $user->mahasiswa_id)
                ->whereNotNull('tahun_lulus')
                ->exists();

            if(!$isLulus){
                return back()
                    ->with('error', 'Tanggal kelulusan Anda belum ditetapkan.');
            }
        }

        $periode_semester = PeriodeSemester::active()->firstOrFail();

        $template = TemplateSurat::findOrFail($request->template_surat_id);

        $dynamicFields = $template->dynamic_fields ?? [];

        $showFields = [
            'nama_mahasiswa' => $user->name,
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
            'tanggal_lulus',
            'kode_mata_kuliah',
            'nama_mata_kuliah',
        ];

        $showFieldKeys = array_keys($showFields);
        $excludeFields = array_merge($showFieldKeys, $systemFieldKeys);

        $formFields = array_values(
            array_diff($dynamicFields, $excludeFields)
        );

        return view('pages.pengajuan.create',
            compact(
                'template',
                'formFields',
                'showFields',
                'mataKuliahOptions',
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
            'fields' => 'nullable|array',
        ]);

        $user = auth()->user();

        $periodeSemester = PeriodeSemester::active()->firstOrFail();

        $mataKuliahId = null;

        if ( (int) $request->template_surat_id === 2 && !empty($request->fields['kode_mata_kuliah'])) {
            $mataKuliah = MataKuliah::where('kode', $request->fields['kode_mata_kuliah'])->first();
            $mataKuliahId = $mataKuliah?->id;
        }

        Pengajuan::create([
            'template_surat_id'   => $request->template_surat_id,
            'mahasiswa_id'        => $user->id,
            'periode_semester_id' => $periodeSemester->id,
            'mata_kuliah_id'      => $mataKuliahId,
            'status'              => Pengajuan::status_dalam_pengajuan,
            'data_pengajuan'      => $request->fields,
        ]);

        return redirect()
            ->route('pengajuan.history')
            ->with('success', 'Pengajuan surat berhasil dibuat');
    }

    /**
     * Detail pengajuan
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load([
            'mahasiswa',
            'templateSurat',
            'periodeSemester',
        ]);

        $template = $pengajuan->templateSurat;
        $periodeSemester = $pengajuan->periodeSemester;

        $dynamicFields = $template->dynamic_fields ?? [];

        $kodeMk = $pengajuan->data_pengajuan['kode_mata_kuliah'] ?? null;

        $mataKuliahLabel = '-';

        if ($kodeMk) {
            $mk = MataKuliah::where('kode', $kodeMk)->first();
            if ($mk) {
                $mataKuliahLabel = $mk->kode . ' - ' . $mk->nama;
            }
        }

        $mahasiswa = collect($pengajuan->data_pengajuan['mahasiswa'] ?? []);

        $showFields = [
            'nama_mahasiswa'   => $pengajuan->mahasiswa->name,
            'email_mahasiswa'  => $pengajuan->mahasiswa->email,
            'nrp_mahasiswa'    => $pengajuan->mahasiswa->nrp,
            'alamat_mahasiswa' => $pengajuan->mahasiswa->alamat,
            'periode_semester' => $periodeSemester->nama,
        ];

        $systemFieldKeys = [
            'nama_kaprodi',
            'nik_kaprodi',
            'kode_surat',
            'tanggal_surat',
            'prodi_mahasiswa',
            'tanggal_lulus',
            'kode_mata_kuliah',
            'nama_mata_kuliah',
        ];

        $formFields = array_values(
            array_diff($dynamicFields, array_keys($showFields), $systemFieldKeys)
        );

        return view('pages.pengajuan.show', compact(
            'template',
            'formFields',
            'showFields',
            'pengajuan',
            'mataKuliahLabel'
        ));
    }

    public function edit(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== Pengajuan::status_dalam_pengajuan) {
            abort(403, 'Pengajuan tidak bisa diedit');
        }

        $mataKuliahOptions = [];

        if ($pengajuan->template_surat_id == 2) {

            $kurikulumAktif = Kurikulum::where('status', 1)->firstOrFail();

            $mataKuliahOptions = MataKuliah::where('kurikulum_id', $kurikulumAktif->id)
            ->orderBy('kode')
            ->get()
            ->mapWithKeys(function ($mk) {
                return [
                    $mk->kode => "{$mk->kode} - {$mk->nama}"
                ];
            });
        }

        $template = $pengajuan->templateSurat;
        $periodeSemester = $pengajuan->periodeSemester;

        $dynamicFields = $template->dynamic_fields ?? [];

        $showFields = [
            'nama_mahasiswa'   => $pengajuan->mahasiswa->name,
            'email_mahasiswa'  => $pengajuan->mahasiswa->email,
            'nrp_mahasiswa'    => $pengajuan->mahasiswa->nrp,
            'alamat_mahasiswa' => $pengajuan->mahasiswa->alamat,
            'periode_semester' => $periodeSemester->nama,
        ];

        $systemFieldKeys = [
            'nama_kaprodi',
            'nik_kaprodi',
            'kode_surat',
            'tanggal_surat',
            'prodi_mahasiswa',
            'tanggal_lulus',
            'kode_mata_kuliah',
            'nama_mata_kuliah',
        ];

        $formFields = array_values(
            array_diff($dynamicFields, array_keys($showFields), $systemFieldKeys)
        );

        return view('pages.pengajuan.edit',
            compact(
                'template',
                'formFields',
                'showFields',
                'pengajuan' ,
                'mataKuliahOptions',
                )
        );
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== Pengajuan::status_dalam_pengajuan) {
            abort(403, 'Pengajuan tidak bisa diedit');
        }

        $request->validate([
            'fields' => 'nullable|array',
        ]);

        $pengajuan->update([
            'data_pengajuan' => $request->fields,
        ]);

        return redirect()
            ->route('pengajuan.history')
            ->with('success', 'Pengajuan berhasil diperbarui');
    }

    public function destroy(Pengajuan $pengajuan)
    {
         $pengajuan->update([
            'status' => Pengajuan::status_dibatalkan,
        ]);

        return redirect()
            ->route('pengajuan.history')
            ->with('success', 'Pengajuan berhasil dibatalkan');
    }

    public function history(){

        $user = auth()->user();

        $pengajuans = Pengajuan::with([
            'templateSurat',
            'periodeSemester',
            'user',
        ])
        ->where('mahasiswa_id', $user->id)
        ->orderByDesc('created_at')
        ->get();

    return view('pages.pengajuan.history', compact('pengajuans'));
    }
}
