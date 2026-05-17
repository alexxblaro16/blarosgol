<?php

namespace Database\Seeders;

use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $alex = User::where('email', 'alejandro@mundial2026.test')->first();
        $rodri = User::where('email', 'rodrigo@mundial2026.test')->first();
        $manu = User::where('email', 'manuel@mundial2026.test')->first();

        if (!$alex) {
            return;
        }

        $community = Community::create([
            'creator_id' => $alex->id,
            'name' => 'Quiniela UDIT',
            'description' => 'Liga oficial de la asignatura Back-End I',
        ]);

        // Alex (creador) ya aceptado
        CommunityMember::create([
            'community_id' => $community->id,
            'user_id' => $alex->id,
            'status' => 'accepted',
            'joined_at' => now(),
        ]);

        // Rodrigo aceptado
        if ($rodri) {
            CommunityMember::create([
                'community_id' => $community->id,
                'user_id' => $rodri->id,
                'status' => 'accepted',
                'joined_at' => now(),
            ]);
        }

        // Manu pendiente
        if ($manu) {
            CommunityMember::create([
                'community_id' => $community->id,
                'user_id' => $manu->id,
                'status' => 'pending',
            ]);
        }
    }
}
