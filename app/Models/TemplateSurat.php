<?php

namespace App\Models;

use App\Concerns\BelongToProgramStudi;
use App\Concerns\SmartDelete;
use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    use SmartDelete;
    use BelongToProgramStudi;

    protected $fillable = [
        'nama', 'kode', 'deskripsi', 'xml_content', 'dynamic_fields', 'status'
    ];

    public function pengajuan(){
        return $this->hasMany(Pengajuan::class);
    }
}
