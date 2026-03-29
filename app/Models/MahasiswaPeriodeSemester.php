<?php

namespace App\Models;

use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class MahasiswaPeriodeSemester extends Model
{
    use SmartDelete;

    const status_cuti = 0;
    const status_aktif = 1;
    const status_tanpa_kabar = 2;
    const status_lulus = 3;
    protected $fillable = [
        'periode_semester_id', 'mahasiswa_id', 'status', 'deskripsi'
    ];

    public static $statusMapping =[
        'cuti' => self::status_cuti,
        'aktif' => self::status_aktif,
        'tanpa_kabar' => self::status_tanpa_kabar,
        'lulus' => self::status_lulus
    ];


    public function getStatusTextAttribute(){
        $mapping = [
          1 => 'Aktif',
          0 => 'Cuti',
          2 => 'Tanpa Kabar',
          3 => 'Lulus'
        ];

        return $mapping[$this->status] ?? 'Tidak diketahui';
    }

    public static function convertStatus($status){
        $status = strtolower(trim($status));
        return self::$statusMapping[$status];
    }

    public function mahasiswa(){
        return $this->belongsTo(Mahasiswa::class);
    }

    public function periodeSemester(){
        return $this->belongsTo(PeriodeSemester::class);
    }
}
