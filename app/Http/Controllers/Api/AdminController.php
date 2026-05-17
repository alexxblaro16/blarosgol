<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportMatchesRequest;
use App\Http\Requests\UpdateMatchResultRequest;
use App\Models\FootballMatch;
use App\Models\TournamentPhase;
use App\Services\MatchImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(private MatchImporter $importer)
    {
    }

    public function importMatches(ImportMatchesRequest $request): JsonResponse
    {
        try {
            if ($request->hasFile('file')) {
                $result = $this->importer->fromUploadedFile($request->file('file'));
            } else {
                $result = $this->importer->fromArray($request->validated()['matches']);
            }
        } catch (\Throwable $e) {
            return $this->error('Error importando partidos: ' . $e->getMessage(), null, 422);
        }

        return $this->success($result, 'Importación completada', 201);
    }

    public function updateMatchResult(UpdateMatchResultRequest $request, FootballMatch $match): JsonResponse
    {
        $match->update([
            'home_score' => $request->validated()['home_score'],
            'away_score' => $request->validated()['away_score'],
            'status' => 'played',
        ]);

        // El observer recalcula automáticamente los puntos de todas las predicciones del partido

        return $this->success($match->fresh(), 'Resultado actualizado y puntos recalculados');
    }

    public function activatePhase(Request $request, string $code): JsonResponse
    {
        $phase = TournamentPhase::where('code', $code)->first();
        if (!$phase) {
            return $this->error('Fase no encontrada', null, 404);
        }

        TournamentPhase::query()->update(['is_current' => false]);
        $phase->update(['is_current' => true, 'is_active' => true]);

        return $this->success($phase->fresh(), 'Fase activada como actual');
    }

    public function phases(): JsonResponse
    {
        return $this->success(TournamentPhase::orderBy('order')->get());
    }

    public function publicPhases(): JsonResponse
    {
        // Endpoint público (autenticado) para que cualquier usuario vea las fases
        return $this->success(TournamentPhase::orderBy('order')->get());
    }
}
