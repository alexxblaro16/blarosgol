<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TournamentPhase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'order',
        'is_current',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function matches(): HasMany
    {
        return $this->hasMany(FootballMatch::class, 'phase_id');
    }

    public static function current(): ?self
    {
        return self::where('is_current', true)->first();
    }
}
