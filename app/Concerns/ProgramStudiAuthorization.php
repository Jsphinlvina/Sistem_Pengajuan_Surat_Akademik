<?php

namespace App\Concerns;

use App\Models\User;

trait ProgramStudiAuthorization
{
    protected function sameProgramStudi(User $user, $model): bool
    {
        return $user->program_studi_id === $model->program_studi_id;
    }
}
