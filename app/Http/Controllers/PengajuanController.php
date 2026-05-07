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
use App\Notifications\PengajuanBaruNotification;
use App\Notifications\PengajuanStatusNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Service\SuratDataBuilder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;


class PengajuanController extends Controller
{

    public function index()
    {
        $templateSurats = TemplateSurat::where('status', 1)->get();

        return view('pages.pengajuan.index', compact('templateSurats'));
    }

    public function redirectHalamanPengajuan(Request $request)
    {
        $request->validate([
            'template_surat_id' => 'required|exists:template_surats,id',
        ]);

        $user = auth()->user();

        $isAktif = MahasiswaPeriodeSemester::where('mahasiswa_id', $user->id)
            ->where('status', 1)
            ->whereHas('periodeSemester', fn($q) => $q->active())
            ->exists();

        if (! $isAktif && $user->tahun_lulus == null) {
            return redirect()
                ->back()
                ->with('error', 'Anda tidak aktif pada periode semester saat ini.');
        }

        $mataKuliahOptions = [];

        if ($request->template_surat_id != 3) {
            $isLulus = $user->tahun_lulus;
            if ($isLulus) {
                return back()
                    ->with('error', 'Anda telah ditanyakan lulus.');
            }
        }

        if ($request->template_surat_id == 2) {
            $mataKuliahOptions = MataKuliah::optionsByKurikulumAktif();
        }

        if ($request->template_surat_id == 3) {
            $isLulus = $user->tahun_lulus;

            $suratPengajuan = Pengajuan::where('template_surat_id', $request->template_surat_id)
                ->where('mahasiswa_id', $user->id)
                ->whereIn('status', [Pengajuan::status_disetujui, Pengajuan::status_dalam_pengajuan,]);

            if ($suratPengajuan->exists()) {
                return back()
                    ->with('error', 'Anda sudah pernah mengajukan SKL.');
            }

            if ($isLulus == null) {
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

        if ($request->template_surat_id == 1) {
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

        return view(
            'pages.pengajuan.create',
            compact(
                'template',
                'formFields',
                'showFields',
                'mataKuliahOptions',
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_surat_id' => 'required|exists:template_surats,id',
            'fields' => 'nullable|array',
        ]);

        $user = auth()->user();

        $periodeSemester = PeriodeSemester::active()->firstOrFail();

        $mataKuliahId = null;

        if ((int) $request->template_surat_id === 2 && !empty($request->fields['kode_mata_kuliah'])) {
            $mataKuliah = MataKuliah::where('kode', $request->fields['kode_mata_kuliah'])->first();
            $mataKuliahId = $mataKuliah?->id;
        }

        $pengajuan = Pengajuan::create([
            'template_surat_id'   => $request->template_surat_id,
            'mahasiswa_id'        => $user->id,
            'periode_semester_id' => $periodeSemester->id,
            'mata_kuliah_id'      => $mataKuliahId,
            'status'              => Pengajuan::status_dalam_pengajuan,
            'data_pengajuan'      => $request->fields,
        ]);

        $staffs = User::where('role', 1)
            ->where('program_studi_id', auth()->user()->program_studi_id)
            ->get();

        Notification::send($staffs, new PengajuanBaruNotification($pengajuan));

        return redirect()
            ->route('pengajuan.history')
            ->with('success', 'Pengajuan surat berhasil dibuat');
    }

//    public function show(Pengajuan $pengajuan)
//    {
//        //
//    }

    public function detail(Pengajuan $pengajuan)
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

        if ($pengajuan->template_surat_id == 1) {
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

        return view(
            'pages.pengajuan.edit',
            compact(
                'template',
                'formFields',
                'showFields',
                'pengajuan',
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

    public function history()
    {
        $mahasiswa = auth('mahasiswa')->user();

        $pengajuans = Pengajuan::with(['templateSurat', 'periodeSemester', 'mahasiswa'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.pengajuan.history', compact('pengajuans'));
    }

    public function prosesPengajuan()
    {
        $user = auth()->user()->program_studi_id;

        $pengajuans = Pengajuan::with(['templateSurat', 'periodeSemester', 'mahasiswa'])
            ->where('status', '=', 1)
            ->byProgramStudi($user)
            ->orderBy('updated_at')
            ->get();

        return view('pages.pengajuan.proses', compact('pengajuans'));
    }

    public function prosesDetail(Pengajuan $pengajuan)
    {
        abort_if($pengajuan->status !== Pengajuan::status_dalam_pengajuan, 403);

        if ($pengajuan->templateSurat->nama == 'Laporan Hasil Studi Mahasiswa') {
            return view('pages.pengajuan.lhsm',  [
                'pengajuan' => $pengajuan,
                'template'  => $pengajuan->templateSurat,
            ]);
        }

        return view('pages.pengajuan.form-proses', [
            'pengajuan' => $pengajuan,
            'template'  => $pengajuan->templateSurat,
        ]);
    }

    public function previewFinal(Pengajuan $pengajuan)
    {
        abort_if($pengajuan->status !== Pengajuan::status_dalam_pengajuan, 403);

        $template = $pengajuan->templateSurat;
        $programStudi = $template->programStudi;

        $kopSuratPath = null;
        if ($programStudi && $programStudi->kop_surat) {
            $kopSuratPath = storage_path('app/public/' . $programStudi->kop_surat);
        }

        $builder = new SuratDataBuilder();
        $kodeSurat = $builder->generateNomorSuratFinal($pengajuan);

        $data = SuratDataBuilder::fromPengajuan($pengajuan);

        $data['kode_surat'] = $kodeSurat;

        $qr = SuratDataBuilder::generateQrImage($pengajuan);
        $data['qr_code'] = view('components.qr-code', [
            'qr' => $qr,
            'tanggal_surat' => $data['tanggal_surat'],
            'nama_kaprodi' => $data['nama_kaprodi'],
        ])->render();

        $html = Blade::render(
            html_entity_decode($template->xml_content, ENT_QUOTES | ENT_HTML5),
            $data
        );

        $pdf = Pdf::loadView('pdf.template-surat', [
            'content' => $html,
            'kop_surat_path' => $kopSuratPath,
        ]);

        return $pdf->stream('preview-final.pdf');
    }


    private function generateFinalPdf(Pengajuan $pengajuan): string
    {
        $template = $pengajuan->templateSurat;
        $programStudi = $template->programStudi;

        $kopSuratPath = null;
        if ($programStudi && $programStudi->kop_surat) {
            $kopSuratPath = storage_path('app/public/' . $programStudi->kop_surat);
        }

        $builder = new SuratDataBuilder();
        $kodeSurat = $builder->generateNomorSuratFinal($pengajuan);

        $data = SuratDataBuilder::fromPengajuan($pengajuan);
        $data['kode_surat'] = $kodeSurat;

        $qr = SuratDataBuilder::generateQrImage($pengajuan);
        $data['qr_code'] = view('components.qr-code', [
            'qr' => $qr,
            'tanggal_surat' => $data['tanggal_surat'],
            'nama_kaprodi' => $data['nama_kaprodi'],

        ])->render();

        $html = Blade::render(
            html_entity_decode($template->xml_content, ENT_QUOTES | ENT_HTML5),
            $data
        );

        $pdf = Pdf::loadView('pdf.template-surat', [
            'content' => $html,
            'kop_surat_path' => $kopSuratPath,
        ]);

        $fileName = 'surat/' . uniqid() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        return $fileName;
    }

    public function accept(Pengajuan $pengajuan)
    {
        DB::transaction(function () use ($pengajuan) {

            $pengajuan = Pengajuan::lockForUpdate()->findOrFail($pengajuan->id);

            abort_if($pengajuan->status !== Pengajuan::status_dalam_pengajuan, 403);

            $builder = new SuratDataBuilder();
            $kodeSurat = $builder->generateNomorSuratFinal($pengajuan);

            $filePath = $this->generateFinalPdf($pengajuan);

            $dataPengajuan = $pengajuan->data_pengajuan ?? [];
            $dataPengajuan['kode_surat'] = $kodeSurat;

            $pengajuan->update([
                'status' => Pengajuan::status_disetujui,
                'data_pengajuan' => $dataPengajuan,
                'file_path' => $filePath,
                'user_id' => auth()->id(),
            ]);

            $pengajuan->mahasiswa->notify(new PengajuanStatusNotification($pengajuan));
        });

        return redirect()
            ->route('pengajuan.proses.pengajuan')
            ->with('success', 'Pengajuan berhasil disetujui');
    }

    public function reject(Request $request, Pengajuan $pengajuan)
    {
        DB::transaction(function () use ($pengajuan) {

            $pengajuan = Pengajuan::lockForUpdate()->findOrFail($pengajuan->id);

            abort_if($pengajuan->status !== Pengajuan::status_dalam_pengajuan, 403);

            $pengajuan->update([
                'status'  => Pengajuan::status_ditolak,
                'user_id' => auth()->id(),
            ]);

            $pengajuan->mahasiswa->notify(new PengajuanStatusNotification($pengajuan));
        });

        return redirect()
            ->route('pengajuan.proses.pengajuan')
            ->with('success', 'Pengajuan berhasil ditolak');
    }

    public function historyPengajuanStaff()
    {

        $user = auth()->user()->program_studi_id;

        $pengajuans = Pengajuan::with(['templateSurat', 'periodeSemester', 'mahasiswa'])
            ->where('status', '!=', 1)
            ->byProgramStudi($user)
            ->orderByDesc('updated_at')
            ->get();

        return view('pages.pengajuan.history', compact('pengajuans'));
    }

    public function download(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== Pengajuan::status_disetujui) {
            abort(403, 'Surat belum tersedia untuk diunduh');
        }

        if (!$pengajuan->file_path || !Storage::disk('public')->exists($pengajuan->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        $fileNameRaw = $pengajuan->data_pengajuan['kode_surat'] ?? $pengajuan->id;
        $fileNameSafe = str_replace(['/', '\\'], '-', $fileNameRaw);
        $fileName = 'Surat-' . $fileNameSafe . '.pdf';

        return Storage::disk('public')->download($pengajuan->file_path, $fileName);
    }

    //    Buat urus Laporan Hasil Studi Mahasiswa
    public function previewLhsm(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:2048'
        ]);

        $tempPath = $request->file('file')->store('temp/lhsm', 'public');

        session(['lhsm_file_' . $pengajuan->id => $tempPath]);

        return view('pages.pengajuan.lhsm-preview', [
            'pengajuan' => $pengajuan,
            'file' => asset('storage/' . $tempPath)
        ]);
    }

    public function acceptLhsm(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'x' => 'required',
            'y' => 'required',
            'canvas_width' => 'required',
            'canvas_height' => 'required',
        ]);

        $tempPath = session('lhsm_file_' . $pengajuan->id);
        abort_if(!$tempPath, 400, 'File belum diupload');

        $pdf = new Fpdi();
        $fullPath = storage_path('app/public/' . $tempPath);
        $pageCount = $pdf->setSourceFile($fullPath);

        $template = $pengajuan->templateSurat;
        $programStudi = $template->programStudi;

        $kopSuratPath = null;
        if ($programStudi && $programStudi->kop_surat) {
            $kopSuratPath = storage_path('app/public/' . $programStudi->kop_surat);
        }

        $qr = SuratDataBuilder::generateQrImage($pengajuan);
        $qrFile = storage_path('app/temp_qr_' . $pengajuan->id . '.png');
        file_put_contents($qrFile, base64_decode(explode(',', $qr)[1]));

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

            $pdf->useTemplate($tpl);

            if ($i == 1 && $kopSuratPath && file_exists($kopSuratPath)) {
                $pdf->Image($kopSuratPath, 0, 0, $size['width']);
            }

            if ($i == 1) {
                $qrSizeMm = 23;

                $centerXmm = ($request->x / $request->canvas_width) * $size['width'];
                $centerYmm = ($request->y / $request->canvas_height) * $size['height'];

                $startX = $centerXmm - ($qrSizeMm / 2);
                $startY = $centerYmm - ($qrSizeMm / 2) + 5;

                $pdf->SetFont('Arial', 'B', 10);

                $text = 'U.B.';
                $textWidth = $pdf->GetStringWidth($text);

                $textX = $startX + ($qrSizeMm / 2) - ($textWidth / 2);
                $textY = $startY - 2;

                $pdf->Text($textX, $textY, $text);

                $pdf->Image($qrFile, $startX, $startY, $qrSizeMm, $qrSizeMm);
            }
        }

        $outputPath = 'surat/' . uniqid() . '.pdf';
        Storage::disk('public')->put($outputPath, $pdf->Output('S'));

        if (file_exists($qrFile)) unlink($qrFile);

        $pengajuan->update([
            'file_path' => $outputPath,
            'status' => Pengajuan::status_disetujui,
            'user_id' => auth()->id(),
        ]);

        Storage::disk('public')->delete($tempPath);
        session()->forget('lhsm_file_' . $pengajuan->id);

        $pengajuan->mahasiswa->notify(new PengajuanStatusNotification($pengajuan));

        return redirect()->route('pengajuan.proses.pengajuan')
            ->with('success', 'Laporan Hasil Studi Mahasiswa berhasil disetujui');
    }
}
