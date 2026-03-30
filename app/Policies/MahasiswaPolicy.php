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
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mahasiwa $mahasiwa): bool
    {
        return $this->sameProgramStudi($user, $mahasiwa);
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
    public function update(User $user, Mahasiwa $mahasiwa): bool
    {
        return  $this->sameProgramStudi($user, $mahasiwa);;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mahasiwa $mahasiwa): bool
    {
        return  $this->sameProgramStudi($user, $mahasiwa);;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mahasiwa $mahasiwa): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mahasiwa $mahasiwa): bool
    {
        return false;
    }
}
