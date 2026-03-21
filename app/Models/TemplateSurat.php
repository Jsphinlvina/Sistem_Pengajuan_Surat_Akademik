<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $fillable = [
        'nama', 'kode', 'deskripsi', 'xml_content', 'dynamic_fields', 'status'
    ];

    public function pengajuan(){
        return $this->hasMany(Pengajuan::class);
    }
}
