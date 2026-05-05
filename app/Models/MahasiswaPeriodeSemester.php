<?php

namespace App\Models;

use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class MahasiswaPeriodeSemester extends Model
{
    use SmartDelete;

    protected $fillable = [
        'periode_semester_id', 'mahasiswa_id', 'status', 'deskripsi'
    ];

    public const mapping = [
        0 => 'Cuti',
        1 => 'Aktif',
        2 => 'Tanpa Kabar',
        3 => 'Lulus',
        4 => 'Sedang Kampus Merdeka'
    ];

    public function getStatusTextAttribute(){
        return self::mapping[$this->status] ?? 'Tidak diketahui';
    }

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }

    public function periodeSemester(){
        return $this->belongsTo(PeriodeSemester::class);
    }
}
