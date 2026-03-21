<?php

namespace App\Models;

use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use SmartDelete;

    protected $fillable = [
      'kode', 'nama', 'kurikulum_id', 'status'
    ];

    public function kurikulum(){
        return $this->belongsTo(Kurikulum::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }
}
