<?php

namespace App\Observers;

use App\Models\FootballMatch;

class MatchObserver
{
    public function saved(FootballMatch $match): void
    {
        // Cuando cambia el resultado del partido, recalculamos las predicciones
        if (!$match->wasChanged(['home_score', 'away_score', 'status'])) {
            return;
        }

        if (!$match->hasResult()) {
            return;
        }

        foreach ($match->predictions as $prediction) {
            $prediction->points = $prediction->calculatePoints($match);
            $prediction->saveQuietly();
        }
    }
}
