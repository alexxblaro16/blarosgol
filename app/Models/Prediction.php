<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'match_id',
        'home_score',
        'away_score',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'home_score' => 'integer',
            'away_score' => 'integer',
            'points' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function calculatePoints(FootballMatch $match): int
    {
        if (!$match->hasResult()) {
            return 0;
        }

        // 3 puntos: resultado exacto
        if ($this->home_score === $match->home_score && $this->away_score === $match->away_score) {
            return 3;
        }

        // 1 punto: ganador acertado (incluido empate)
        $realWinner = $match->home_score <=> $match->away_score; // -1, 0, 1
        $predWinner = $this->home_score <=> $this->away_score;

        return $realWinner === $predWinner ? 1 : 0;
    }
}
