<?php

namespace App\Models\Scope;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;

class ProgramStudiScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check()) {
            $builder->where(
                $model->getTable() . '.program_studi_id',
                auth()->user()->program_studi_id
            );
        }
    }
}
