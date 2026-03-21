<?php

namespace App\Models;

use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model
{
    use SmartDelete;
    protected $fillable = [
        'nama', 'status', 'program_studi_id'
    ];

    public function programStudi(){
        return $this->belongsTo(ProgramStudi::class);
    }

    public function mataKuliah(){
        return $this->hasMany(MataKuliah::class);
    }

    public function smartDeleteCascade(): bool
    {
        foreach ($this->mataKuliah as $mk) {
            if (! $mk->smartDelete(['pengajuan'])) {
                return false;
            }
        }
        $this->delete();
        return true;
    }
}
