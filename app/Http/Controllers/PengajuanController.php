<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\Mahasiswa;
use App\Models\MahasiswaPeriodeSemester;
use App\Models\MataKuliah;
use App\Models\Pengajuan;
use App\Models\PeriodeSemester;
use App\Models\TemplateSurat;
use App\Models\User;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{

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

        if (! $isAktif && $user->tahun_lulus == null) {
            return redirect()
                ->back()
                ->with('error', 'Anda tidak aktif pada periode semester saat ini.');
        }

        $mataKuliahOptions = [];

        if ($request->template_surat_id == 2) {
           $mataKuliahOptions = MataKuliah::optionsByKurikulumAktif();
        }

        if ($request->template_surat_id == 3) {
             $isLulus = $user->tahun_lulus;

            if($isLulus == null){
                return back()
                    ->with('error', 'Tanggal kelulusan Anda belum ditetapkan.');
            }
        }

        $periode_semester = PeriodeSemester::active()->firstOrFail();

        $template = TemplateSurat::findOrFail($request->template_surat_id);

        $dynamicFields = $template->dynamic_fields ?? [];

        $showFields = [
            'periode_semester' => $periode_semester->nama,
            'nrp_mahasiswa' => $user->nrp,
            'nama_mahasiswa' => $user->name,
            'email_mahasiswa' => $user->email,
        ];

        if ($request->template_surat_id == 1){
           $showFields['alamat_mahasiswa'] = $user->alamat;
        } elseif ($request->template_surat_id == 3) {
           $showFields['tahun_lulus'] = $user->tahun_lulus;
        }

        $systemFieldKeys = [
            'nama_kaprodi',
            'nik_kaprodi',
            'kode_surat',
            'tanggal_surat',
            'prodi_mahasiswa',
            'kode_mata_kuliah',
            'nama_mata_kuliah',
            'tanggal_lulus'
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
            $mataKuliahOptions = MataKuliah::optionsByKurikulumAktif();
        }

        $template = $pengajuan->templateSurat;
        $periodeSemester = $pengajuan->periodeSemester;
        $mahasiswa = $pengajuan->mahasiswa;

        $dynamicFields = $template->dynamic_fields ?? [];

        $showFields = [
            'nama_mahasiswa'   => $mahasiswa->name,
            'email_mahasiswa'  => $mahasiswa->email,
            'nrp_mahasiswa'    => $mahasiswa->nrp,
            'periode_semester' => $periodeSemester->nama,
        ];

        if ($pengajuan->template_surat_id == 1){
           $showFields['alamat_mahasiswa'] = $mahasiswa->alamat;
        }

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

        $mataKuliahId = $pengajuan->mata_kuliah_id;

        if ($pengajuan->template_surat_id == 2 && !empty($request->fields['kode_mata_kuliah'])) {
            $mk = MataKuliah::where('kode', $request->fields['kode_mata_kuliah'])->first();
            $mataKuliahId = $mk?->id;
        }

        $pengajuan->update([
            'data_pengajuan' => $request->fields,
            'mata_kuliah_id' => $mataKuliahId,
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
        $mahasiswa = auth('mahasiswa')->user();

        $pengajuans = Pengajuan::with(['templateSurat', 'periodeSemester', 'mahasiswa'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get();

            return view('pages.pengajuan.history', compact('pengajuans'));
    }

    public function historyPengajuan(){

        $user = auth()->user();

        $pengajuans = Pengajuan::with(['templateSurat', 'periodeSemester', 'mahasiswa'])
            ->where('status', '!=', 1)
            ->byProgramStudi($user->program_studi_id)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.pengajuan.history', compact('pengajuans'));
    }
}
