<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'name',
        'code',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $community) {
            if (empty($community->code)) {
                $community->code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(CommunityMember::class);
    }

    public function acceptedMembers()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot('status', 'joined_at')
            ->wherePivot('status', 'accepted')
            ->withTimestamps();
    }

    public function pendingMembers()
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withPivot('status', 'joined_at')
            ->wherePivot('status', 'pending')
            ->withTimestamps();
    }
}
