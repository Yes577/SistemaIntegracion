<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;
use App\Models\TipoRol;

class EventoPolicy
{
    /**
     * Determine whether the user can view any modelo.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the modelo.
     */
    public function view(User $user, Evento $evento): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create modelos.
     */
    public function create(User $user): bool
    {
        return $user->id_tipo_rol === TipoRol::ADMIN_ID;
    }

    /**
     * Determine whether the user can update the modelo.
     */
    public function update(User $user, Evento $evento): bool
    {
        return $user->id_tipo_rol === TipoRol::ADMIN_ID;
    }

    /**
     * Determine whether the user can delete the modelo.
     */
    public function delete(User $user, Evento $evento): bool
    {
        return $user->id_tipo_rol === TipoRol::ADMIN_ID;
    }

    /**
     * Determine whether the user can restore the modelo.
     */
    public function restore(User $user, Evento $evento): bool
    {
        return $user->id_tipo_rol === TipoRol::ADMIN_ID;
    }

    /**
     * Determine whether the user can permanently delete the modelo.
     */
    public function forceDelete(User $user, Evento $evento): bool
    {
        return $user->id_tipo_rol === TipoRol::ADMIN_ID;
    }
}
