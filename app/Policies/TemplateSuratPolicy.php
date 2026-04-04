<?php

namespace App\Policies;

use App\Concerns\ProgramStudiAuthorization;
use App\Models\TemplateSurat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TemplateSuratPolicy
{
    use ProgramStudiAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TemplateSurat $templateSurat): bool
    {
        return $this->sameProgramStudi($user, $templateSurat);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TemplateSurat $templateSurat): bool
    {
        return $this->sameProgramStudi($user, $templateSurat);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TemplateSurat $templateSurat): bool
    {
        return $this->sameProgramStudi($user, $templateSurat);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TemplateSurat $templateSurat): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TemplateSurat $templateSurat): bool
    {
        return false;
    }
}
