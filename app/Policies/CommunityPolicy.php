<?php

namespace App\Policies;

use App\Models\Community;
use App\Models\User;

class CommunityPolicy
{
    // Ver comunidad: ser creador o miembro aceptado
    public function view(User $user, Community $community): bool
    {
        if ($community->creator_id === $user->id) {
            return true;
        }

        return $community->memberships()
            ->where('user_id', $user->id)
            ->where('status', 'accepted')
            ->exists();
    }

    // Aceptar solicitudes y eliminar miembros: SOLO el creador
    public function manage(User $user, Community $community): bool
    {
        return $community->creator_id === $user->id;
    }
}
