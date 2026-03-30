<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class PeriodeSemester extends Model
{
    use SmartDelete;
    use BelongToProgramStudi;

    protected $fillable = [
        'nama', 'status', 'dosen_id', 'program_studi_id'
    ];

    public function mahasiswaPeriodeSemester(){
        return $this->hasMany(MahasiswaPeriodeSemester::class);
    }

    public function pengajuan(){
        return $this->hasMany(Pengajuan::class);
    }

    public function scopeActive($query){
        return $query->where('status', 'aktif');
    }

    public function dosen(){
        return $this->belongsTo(Dosen::class);
    }
}
