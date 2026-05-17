<?php

namespace Database\Seeders;

use App\Models\TournamentPhase;
use Illuminate\Database\Seeder;

class TournamentPhaseSeeder extends Seeder
{
    public function run(): void
    {
        // Fases reales del Mundial 2026 (formato 48 equipos: grupos + 32avos)
        $phases = [
            ['name' => 'Fase de Grupos', 'code' => 'GROUP', 'order' => 1, 'is_current' => true, 'is_active' => true],
            ['name' => 'Dieciseisavos de Final', 'code' => 'R32', 'order' => 2],
            ['name' => 'Octavos de Final', 'code' => 'R16', 'order' => 3],
            ['name' => 'Cuartos de Final', 'code' => 'QF', 'order' => 4],
            ['name' => 'Semifinales', 'code' => 'SF', 'order' => 5],
            ['name' => 'Tercer y Cuarto Puesto', 'code' => 'TPP', 'order' => 6],
            ['name' => 'Final', 'code' => 'F', 'order' => 7],
        ];

        foreach ($phases as $phase) {
            TournamentPhase::updateOrCreate(['code' => $phase['code']], $phase);
        }
    }
}
