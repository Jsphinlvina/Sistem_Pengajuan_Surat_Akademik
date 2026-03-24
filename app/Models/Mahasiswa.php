<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use BelongToProgramStudi;

    protected $fillable = [
      'nama', 'email', 'nrp', 'alamat', 'program_studi_id'
    ];

    public function programStudi(){
        return $this->belongsTo(ProgramStudi::class);
    }

    public function pengajuan(){
        return $this->hasMany(Pengajuan::class);
    }

    public function mahasiswaPeriodeSemester(){
        return $this->hasMany(MahasiswaPeriodeSemester::class);
    }

    public function statusPeriodeAktif(){
        return $this->hasOne(MahasiswaPeriodeSemester::class)
        ->whereHas('periodeSemester', function($query){
            $query->where('status', 'aktif');
        });
    }
}
