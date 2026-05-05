<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use SmartDelete;
    use BelongToProgramStudi;

    protected $fillable = [
      'nama', 'nik', 'program_studi_id'
    ];

    public function programStudi(){
        return $this->belongsTo(ProgramStudi::class);
    }

    public function periodeSemester(){
        return $this->hasMany(PeriodeSemester::class);
    }

}

