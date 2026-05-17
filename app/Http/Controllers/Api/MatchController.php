<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = FootballMatch::with('phase')->orderBy('kick_off_at');

        if ($request->filled('phase')) {
            $query->whereHas('phase', fn ($q) => $q->where('code', $request->string('phase')));
        }

        if ($request->boolean('played_only')) {
            $query->where('status', 'played');
        }

        return $this->success($query->get());
    }

    public function show(FootballMatch $match): JsonResponse
    {
        return $this->success($match->load('phase'));
    }
}
