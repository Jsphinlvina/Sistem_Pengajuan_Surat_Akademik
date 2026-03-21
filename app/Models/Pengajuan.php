<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    const status_dalam_pengajuan = 0;
    const status_dalam_proses = 1;
    const status_disetujui = 2;
    const status_ditolak = 3;

    protected $fillable =[
      'periode_semester_id', 'mahasiswa_id', 'template_surat_id', 'status', 'catatan', 'file_surat', 'user_id', 'mata_kuliah_id'
    ];

    public function periodeSemester(){
        return $this->belongsTo(PeriodeSemester::class);
    }
    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }
    public function templateSurat(){
        return $this->belongsTo(TemplateSurat::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function mataKuliah(){
        return $this->belongsTo(MataKuliah::class);
    }
}
