<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use SmartDelete;

    const status_dalam_pengajuan = 1;
    const status_disetujui = 2;
    const status_ditolak = 3;
    const status_dibatalkan = 4;

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::status_dalam_pengajuan => 'Dalam Pengajuan',
            self::status_disetujui       => 'Disetujui',
            self::status_ditolak         => 'Ditolak',
            self::status_dibatalkan      => 'Dibatalkan',
            default                      => 'Dalam Pengajuan',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::status_dalam_pengajuan => 'bg-yellow-600',
            self::status_disetujui       => 'bg-green-600',
            self::status_ditolak         => 'bg-red-600',
            self::status_dibatalkan      => 'bg-gray-600',
            default                      => 'bg-yellow-500',
        };
    }

    protected $fillable =[
        'periode_semester_id',
        'mahasiswa_id',
        'template_surat_id',
        'status',
        'catatan',
        'file_surat',
        'user_id',
        'mata_kuliah_id',
        'data_pengajuan'
    ];

    protected $casts = [
        'data_pengajuan' => 'array',
    ];

    public function periodeSemester(){
        return $this->belongsTo(PeriodeSemester::class);
    }
    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }
    public function templateSurat(){
        return $this->belongsTo(TemplateSurat::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function mataKuliah(){
        return $this->belongsTo(MataKuliah::class);
    }
}
