<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MahasiswaPeriodeSemester;
use App\Models\PeriodeSemester;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PeriodeSemesterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PeriodeSemester::class, 'periode_semester');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodeSemesters = PeriodeSemester::all();
        return view('pages.periode-semester.index', compact('periodeSemesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.periode-semester.create');
    }

    public function importMahasiswaPeriodeSemester(Request $request, PeriodeSemester $periodeSemester)
    {
        $this->authorize('update', $periodeSemester);

        $request->validate([
           'file' => 'required|mimes:xls,xlsx, csv'
        ]);

        try {
            $row = Excel::toArray([], $request->file('file'));

            $data = collect($row[0])
                ->skip(1)
                ->map(function ($row) {
                    return [
                        'nrp' => $row[0] ?? null,
                        'status' => $row[1] ?? null,
                    ];
                })
                ->filter(fn($row) => $row['nrp'] && $row['status'])
                ->values()
                ->toArray();

            return redirect()
                ->route('periode-semester.edit', $periodeSemester)
                ->with('preview_mahasiswa_periode', $data);

        } catch (\Throwable $e) {
            return redirect()
                ->route('periode-semester.edit', $periodeSemester)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:periode_semesters|max:255',
            'dosen_id' => 'required',
        ]);

        $programStudiId = auth()->user()->program_studi_id;

        $exists = PeriodeSemester::where('program_studi_id', $programStudiId)
            ->where('nama', $request->nama)
            ->exists();

        if ($exists){
            return back()->with(
              'error',
              "Nama periode '{ $request->nama }' sudah ada di dalam Program Studi ini"
            );
        }

        $isActive = PeriodeSemester::where('program_studi_id', $programStudiId)
            ->where('status', true)
            ->exists();

        $periodeSemester = PeriodeSemester::create([
           'nama' => $request->nama,
           'dosen_id' => $request->dosen_id,
           'program_studi_id' => $programStudiId,
            'status' => $isActive ? false : true,
        ]);

        return redirect()
            ->route('periode-semester.edit',$periodeSemester)
            ->with('success', 'Data Periode Semester berhasil dibuat');
    }

    public function storeImportMahasiswaPeriodeSemester(Request $request, PeriodeSemester $periodeSemester)
    {
        $this->authorize('update', $periodeSemester);

        $data = json_decode($request->data, true);

        foreach ($data as $row) {
            $mahasiswa = Mahasiswa::where('program_studi_id', auth()->user()->program_studi_id)
                ->where('nrp', $row['nrp'])
                ->first();

            if (!$mahasiswa) {
                return back()->with(
                    'error', "Import Gagal : NRP '{$row['nrp']}' tidak ditemukan."
                );
            }

             $exists = MahasiswaPeriodeSemester::where('mahasiswa_id', $mahasiswa->id)
                ->where('periode_semester_id', $periodeSemester->id)
                ->exists();

            if ($exists) {
                return back()->with(
                    'error', "Import Gagal : Mahasiswa dengan NRP '{$row['nrp']}' sudah ada pada periode ini."
                );
            }

            MahasiswaPeriodeSemester::create([
                'mahasiswa_id' => $mahasiswa->id,
                'periode_semester_id' => $periodeSemester->id,
                'status' => $row['status'] ?? 1,
                'deskripsi' => $row['deskripsi'] ?? null,
            ]);
        }
        return redirect()
            ->route('periode-semester.show', $periodeSemester)
            ->with('success', 'Data mahasiswa berhasil diimport');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodeSemester $periodeSemester)
    {
        $periodeSemester->load('mahasiswaPeriodeSemester.mahasiswa');
        return view('pages.periode-semester.show', compact('periodeSemester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeSemester $periodeSemester)
    {
        $periodeSemester->load('mahasiswaPeriodeSemester');
        return view('pages.periode-semester.edit', compact('periodeSemester'));
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

    public function updateStatus(Request $request, PeriodeSemester $periodeSemester)
    {
        $this->authorize('update', $periodeSemester);

        $request->validate([
            'status' => 'required|boolean'
        ]);

        if ($request->status){

            $activePeriode = PeriodeSemester::where('program_studi_id', $periodeSemester->program_studi_id)
                ->where('status', true)
                ->where('id', '!=', $periodeSemester->id)
                ->first();

            if ($activePeriode){
                return back()->with('error',
                    "Tidak dapat mengaktifkan periode semester. Periode Semester '{$activePeriode->nama}' sedang aktif.");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeSemester $periodeSemester)
    {
        $deleted = $periodeSemester->smartDelete(['mahasiswaPeriodeSemester', 'pengajuan']);
        return redirect()
            ->route('periode-semester.index')
            ->with(
                $deleted ? 'success' : 'error',
                $deleted ? 'Periode Semester berhasil dihapus' : 'Periode Semester tidak dapat dihapus karena data sudah digunakan'
            );
    }
}
