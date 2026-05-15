<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Comunidades que ha creado
    public function ownedCommunities(): HasMany
    {
        return $this->hasMany(Community::class, 'creator_id');
    }

    // Filas de pertenencia (pending o accepted)
    public function memberships(): HasMany
    {
        return $this->hasMany(CommunityMember::class);
    }

    // Comunidades a las que pertenece (aceptado)
    public function communities()
    {
        return $this->belongsToMany(Community::class, 'community_members')
            ->withPivot('status', 'joined_at')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }

    public function totalPoints(): int
    {
        return (int) $this->predictions()->sum('points');
    }
}
