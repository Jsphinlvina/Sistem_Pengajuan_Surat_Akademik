<?php

namespace App\Http\Controllers;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kurikulums = Kurikulum::with('programStudi')
            ->whereBelongsTo(auth()->user()->programStudi)
            ->get();
        return view('pages.kurikulum.index', compact('kurikulums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.kurikulum.create');
    }

    public function importMataKuliah(Request $request, Kurikulum $kurikulum)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {

            $rows = Excel::toArray([], $request->file('file'));

            $data = collect($rows[0])
                ->skip(1)
                ->map(function ($row) {
                    return [
                        'kode' => $row[0] ?? null,
                        'nama' => $row[1] ?? null,
                    ];
                })
                ->filter(fn($row) => $row['kode'] && $row['nama'])
                ->values()
                ->toArray();

            return redirect()
                ->route('kurikulum.edit', $kurikulum)
                ->with('preview_mk', $data);

        } catch (\Throwable $e) {

            return redirect()
                ->route('kurikulum.edit', $kurikulum)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kurikulums,nama',
        ]);

        $programStudiId = auth()->user()->program_studi_id;

        $isActive = Kurikulum::where('program_studi_id', $programStudiId)
            ->where('status', true)
            ->exists();

        $kurikulum = Kurikulum::create([
            'nama' => $request->nama,
            'program_studi_id' => $programStudiId,
            'status' => $isActive ? false : true,
        ]);

        return redirect()
            ->route('kurikulum.edit', $kurikulum)
            ->with('success', 'Kurikulum berhasil dibuat');
    }

    public function storeImportMataKuliah(Request $request, Kurikulum $kurikulum)
    {
        $data = json_decode($request->data, true);

        foreach ($data as $row) {

            $exists = MataKuliah::where('kode', $row['kode'])->exists();

            if ($exists) {
                return back()->with(
                    'error', "Import Gagal : kode mata kuliah {$row['kode']} sudah ada."
                );
            }

            MataKuliah::create([
                'kode' => $row['kode'],
                'nama' => $row['nama'],
                'kurikulum_id' => $kurikulum->id,
            ]);
        }
        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('success', 'Mata kuliah berhasil diimport');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kurikulum $kurikulum)
    {
        $kurikulum->load('mataKuliah', 'programStudi');
        return view('pages.kurikulum.show', compact('kurikulum'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kurikulum $kurikulum)
    {
        $kurikulum->load('mataKuliah', 'programStudi');
        return view('pages.kurikulum.edit', compact('kurikulum'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kurikulum $kurikulum)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:kurikulums,nama,' . $kurikulum->id,
            'status' => 'required|boolean',
        ]);

        if ($data['status']) {

            $activeKurikulum  = Kurikulum::where('program_studi_id', $kurikulum->program_studi_id)
                ->where('id', '!=', $kurikulum->id)
                ->where('status', true)
                ->first();

            if ($activeKurikulum ) {
                return back()->with(
                    'error',
                    "Tidak dapat mengaktifkan kurikulum. Kurikulum '{ $activeKurikulum->nama }' sedang aktif"
                );
            }
        }

        $kurikulum->update($data);

        return redirect()
            ->route('kurikulum.show', $kurikulum)
            ->with('success', 'Kurikulum berhasil diperbarui');
    }

    public function updateStatus(Request $request, Kurikulum $kurikulum)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        if ($request->status) {

            $activeKurikulum = Kurikulum::where('program_studi_id', $kurikulum->program_studi_id)
                ->where('status', true)
                ->where('id', '!=', $kurikulum->id)
                ->first();

            if ($activeKurikulum) {
                return back()->with(
                    'error',
                    "Tidak dapat mengaktifkan kurikulum. Kurikulum '{$activeKurikulum->nama}' sedang aktif."
                );
            }
        }

        $kurikulum->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status kurikulum berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kurikulum $kurikulum)
    {
        $deleted = $kurikulum->smartDelete(['mataKuliah']);
        return redirect()->route('kurikulum.index')->with(
            $deleted ? 'success' : 'info',
            $deleted ? 'Kurikulum berhasil dihapus permanen' : 'Kurikulum tidak dapat dihapus karena data sudah digunakan'
        );
    }
}
