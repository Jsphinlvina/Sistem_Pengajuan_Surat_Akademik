<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeSemester extends Model
{
    protected $fillable = [
        'nama', 'status', 'kaprodi'
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
}
