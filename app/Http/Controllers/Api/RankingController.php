<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Prediction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function community(Request $request, Community $community): JsonResponse
    {
        if (!$request->user()->can('view', $community)) {
            return $this->error('No tienes acceso al ranking de esta comunidad', null, 403);
        }

        $memberIds = $community->acceptedMembers()->pluck('users.id');

        $points = Prediction::selectRaw('user_id, COALESCE(SUM(points), 0) as total_points, COUNT(*) as predictions_count')
            ->whereIn('user_id', $memberIds)
            ->groupBy('user_id')
            ->pluck('total_points', 'user_id');

        $counts = Prediction::selectRaw('user_id, COUNT(*) as predictions_count')
            ->whereIn('user_id', $memberIds)
            ->groupBy('user_id')
            ->pluck('predictions_count', 'user_id');

        $members = $community->acceptedMembers()->get(['users.id', 'users.name']);

        $ranking = $members->map(fn ($user) => [
            'user_id' => $user->id,
            'name' => $user->name,
            'total_points' => (int) ($points[$user->id] ?? 0),
            'predictions_count' => (int) ($counts[$user->id] ?? 0),
        ])->sortByDesc('total_points')->values();

        return $this->success([
            'community' => $community->only(['id', 'name', 'code']),
            'ranking' => $ranking,
        ]);
    }
}
