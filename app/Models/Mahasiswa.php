<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use SmartDelete;
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
}
