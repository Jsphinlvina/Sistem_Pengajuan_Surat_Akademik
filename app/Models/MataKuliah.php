<?php

namespace App\Models;

use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use SmartDelete;

    public static function optionsByKurikulumAktif(){
        $kurikulum = Kurikulum::where('status', 1)->firstOrFail();

        return self::where('kurikulum_id', $kurikulum->id)
            ->orderBy('kode')
            ->get()
            ->mapWithKeys(fn ($mk) => [
                $mk->kode => "{$mk->kode} - {$mk->nama}"
            ]);
    }

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
