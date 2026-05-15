<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Se llama FootballMatch porque "Match" es palabra reservada en PHP 8.
// La tabla sigue siendo "matches".
class FootballMatch extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'phase_id',
        'home_team',
        'away_team',
        'home_code',
        'away_code',
        'venue',
        'kick_off_at',
        'home_score',
        'away_score',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kick_off_at' => 'datetime',
            'home_score' => 'integer',
            'away_score' => 'integer',
        ];
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(TournamentPhase::class, 'phase_id');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'match_id');
    }

    public function hasResult(): bool
    {
        return $this->home_score !== null && $this->away_score !== null;
    }

    public function isLockedForPredictions(): bool
    {
        // Se permite modificar una predicción hasta el día anterior al partido
        return now()->startOfDay()->greaterThanOrEqualTo($this->kick_off_at->copy()->startOfDay());
    }
}
