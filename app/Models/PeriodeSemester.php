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
