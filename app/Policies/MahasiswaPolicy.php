<?php

namespace App\Policies;

use App\Concerns\ProgramStudiAuthorization;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MahasiswaPolicy
{
    use ProgramStudiAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mahasiswa $mahasiswa): bool
    {
        return $this->sameProgramStudi($user, $mahasiswa);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mahasiswa $mahasiswa): bool
    {
        return  $this->sameProgramStudi($user, $mahasiswa);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mahasiswa $mahasiswa): bool
    {
        return  $this->sameProgramStudi($user, $mahasiswa);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(): bool
    {
        return false;
    }
}
