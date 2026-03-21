<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaPeriodeSemester extends Model
{
    const status_cuti = 0;
    const status_aktif = 1;
    const status_lulus = 2;

    protected $fillable = [
        'periode_semester_id', 'mahasiswa_id', 'status', 'deskripsi'
    ];

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }

    public function periodeSemester(){
        return $this->belongsTo(PeriodeSemester::class);
    }
}
