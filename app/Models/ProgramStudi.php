<?php

namespace App\Models;

use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use SmartDelete;

    protected $fillable =[
        'kode', 'nama','status','kop_surat'
    ];

    public function kurikulum(){
        return $this->hasMany(Kurikulum::class);
    }

    public function user(){
        return $this->hasMany(User::class);
    }

    public function mahasiswa(){
        return $this->hasMany(Mahasiswa::class);
    }
}
