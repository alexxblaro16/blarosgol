<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PredictionRequest;
use App\Models\FootballMatch;
use App\Models\Prediction;
use App\Models\TournamentPhase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    // Predicciones del usuario (todas las fases)
    public function index(Request $request): JsonResponse
    {
        $predictions = Prediction::with(['match.phase'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->get();

        return $this->success($predictions);
    }

    public function store(PredictionRequest $request): JsonResponse
    {
        $data = $request->validated();

        $match = FootballMatch::with('phase')->find($data['match_id']);

        if (!$match) {
            return $this->error('Partido no encontrado', null, 404);
        }

        // Solo se predice en la fase actual del torneo
        $current = TournamentPhase::current();
        if (!$current || $match->phase_id !== $current->id) {
            return $this->error('Solo se pueden hacer predicciones de la fase actual del torneo', null, 422);
        }

        // No se permite predecir un partido ya bloqueado (día anterior o posterior al partido)
        if ($match->isLockedForPredictions()) {
            return $this->error('Ya no se admiten predicciones para este partido', null, 422);
        }

        // No duplicar predicción
        $existing = Prediction::where('user_id', $request->user()->id)
            ->where('match_id', $match->id)
            ->first();

        if ($existing) {
            return $this->error('Ya tienes una predicción para este partido. Modifícala con PATCH.', null, 409);
        }

        $prediction = Prediction::create([
            'user_id' => $request->user()->id,
            'match_id' => $match->id,
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score'],
        ]);

        return $this->success($prediction->load('match'), 'Predicción guardada', 201);
    }

    public function update(PredictionRequest $request, Prediction $prediction): JsonResponse
    {
        if (!$request->user()->can('update', $prediction)) {
            return $this->error('No puedes modificar predicciones ajenas', null, 403);
        }

        $match = $prediction->match;

        if ($match->isLockedForPredictions()) {
            return $this->error('Solo se puede modificar hasta el día anterior al partido', null, 422);
        }

        $prediction->update($request->validated());

        return $this->success($prediction->fresh()->load('match'), 'Predicción actualizada');
    }

    public function destroy(Request $request, Prediction $prediction): JsonResponse
    {
        if (!$request->user()->can('delete', $prediction)) {
            return $this->error('No puedes borrar predicciones ajenas', null, 403);
        }

        if ($prediction->match->isLockedForPredictions()) {
            return $this->error('Ya no se admiten cambios en este partido', null, 422);
        }

        $prediction->delete();

        return $this->success(null, 'Predicción eliminada');
    }
}
