<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommunityRequest;
use App\Models\Community;
use App\Models\CommunityMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    // Comunidades del usuario (creadas + en las que es miembro aceptado)
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $owned = $user->ownedCommunities()->withCount('acceptedMembers')->get();
        $joined = $user->communities()->withCount('acceptedMembers')->get();

        return $this->success([
            'owned' => $owned,
            'joined' => $joined,
        ]);
    }

    public function store(CreateCommunityRequest $request): JsonResponse
    {
        $community = Community::create([
            'creator_id' => $request->user()->id,
            'name' => $request->validated()['name'],
            'description' => $request->validated()['description'] ?? null,
        ]);

        // El creador es automáticamente miembro aceptado
        CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => $request->user()->id,
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        return $this->success($community, 'Comunidad creada', 201);
    }

    public function show(Request $request, Community $community): JsonResponse
    {
        if (!$request->user()->can('view', $community)) {
            return $this->error('No tienes acceso a esta comunidad', null, 403);
        }

        $community->load(['creator:id,name,email', 'acceptedMembers:id,name,email']);

        return $this->success($community);
    }

    // Pedir unirse a una comunidad por código (crea solicitud pending)
    public function join(Request $request, string $code): JsonResponse
    {
        $community = Community::where('code', $code)->first();

        if (!$community) {
            return $this->error('Comunidad no encontrada', null, 404);
        }

        $existing = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return $this->error('Ya eres miembro de esta comunidad', null, 409);
            }
            return $this->error('Ya tienes una solicitud pendiente', null, 409);
        }

        $member = CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return $this->success([
            'community' => $community->only(['id', 'name', 'code']),
            'membership' => $member,
        ], 'Solicitud enviada. Espera a que el creador la acepte.', 201);
    }

    // Solicitudes pendientes (solo creador)
    public function requests(Request $request, Community $community): JsonResponse
    {
        if (!$request->user()->can('manage', $community)) {
            return $this->error('Solo el creador puede ver las solicitudes', null, 403);
        }

        $pending = $community->pendingMembers()->get(['users.id', 'users.name', 'users.email']);

        return $this->success($pending);
    }

    // Aceptar solicitud (solo creador)
    public function acceptRequest(Request $request, Community $community, int $userId): JsonResponse
    {
        if (!$request->user()->can('manage', $community)) {
            return $this->error('Solo el creador puede aceptar solicitudes', null, 403);
        }

        $member = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->first();

        if (!$member) {
            return $this->error('Solicitud no encontrada', null, 404);
        }

        $member->update([
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        return $this->success($member, 'Solicitud aceptada');
    }

    // Eliminar miembro (solo creador, no a sí mismo)
    public function removeMember(Request $request, Community $community, int $userId): JsonResponse
    {
        if (!$request->user()->can('manage', $community)) {
            return $this->error('Solo el creador puede eliminar miembros', null, 403);
        }

        if ($userId === $community->creator_id) {
            return $this->error('No puedes eliminarte a ti mismo como creador', null, 422);
        }

        $deleted = CommunityMember::where('community_id', $community->id)
            ->where('user_id', $userId)
            ->delete();

        if (!$deleted) {
            return $this->error('Miembro no encontrado', null, 404);
        }

        return $this->success(null, 'Miembro eliminado');
    }
}
