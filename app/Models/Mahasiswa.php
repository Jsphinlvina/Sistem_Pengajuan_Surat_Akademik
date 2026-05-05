<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use Notifiable;
    use SmartDelete;
    use BelongToProgramStudi;

    protected $fillable = [
      'name', 'email', 'nrp', 'alamat', 'tahun_lulus', 'first_time_login', 'program_studi_id'
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
