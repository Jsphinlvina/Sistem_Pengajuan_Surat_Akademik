<?php

namespace App\Concerns;

use App\Models\Scope\ProgramStudiScope;

trait BelongToProgramStudi
{
    protected static function bootBelongToProgramStudi(){
        static::addGlobalScope(new ProgramStudiScope());
    }
}
