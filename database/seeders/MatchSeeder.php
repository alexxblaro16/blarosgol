<?php

namespace Database\Seeders;

use App\Models\FootballMatch;
use App\Models\TournamentPhase;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $groupPhase = TournamentPhase::where('code', 'GROUP')->first();
        if (!$groupPhase) {
            return;
        }

        // Grupos REALES del Mundial FIFA 2026 (sorteo Washington 5/dic/2025)
        $groups = [
            'A' => [['MEX', 'México'], ['RSA', 'Sudáfrica'], ['KOR', 'Corea del Sur'], ['CZE', 'Chequia']],
            'B' => [['CAN', 'Canadá'], ['BIH', 'Bosnia-Herzegovina'], ['QAT', 'Catar'], ['SUI', 'Suiza']],
            'C' => [['BRA', 'Brasil'], ['MAR', 'Marruecos'], ['HAI', 'Haití'], ['SCO', 'Escocia']],
            'D' => [['USA', 'Estados Unidos'], ['PAR', 'Paraguay'], ['AUS', 'Australia'], ['TUR', 'Turquía']],
            'E' => [['GER', 'Alemania'], ['CUW', 'Curazao'], ['CIV', 'Costa de Marfil'], ['ECU', 'Ecuador']],
            'F' => [['NED', 'Países Bajos'], ['JPN', 'Japón'], ['SWE', 'Suecia'], ['TUN', 'Túnez']],
            'G' => [['BEL', 'Bélgica'], ['EGY', 'Egipto'], ['IRN', 'Irán'], ['NZL', 'Nueva Zelanda']],
            'H' => [['ESP', 'España'], ['CPV', 'Cabo Verde'], ['KSA', 'Arabia Saudí'], ['URU', 'Uruguay']],
            'I' => [['FRA', 'Francia'], ['SEN', 'Senegal'], ['IRQ', 'Irak'], ['NOR', 'Noruega']],
            'J' => [['ARG', 'Argentina'], ['ALG', 'Argelia'], ['AUT', 'Austria'], ['JOR', 'Jordania']],
            'K' => [['POR', 'Portugal'], ['COD', 'RD Congo'], ['UZB', 'Uzbekistán'], ['COL', 'Colombia']],
            'L' => [['ENG', 'Inglaterra'], ['CRO', 'Croacia'], ['GHA', 'Ghana'], ['PAN', 'Panamá']],
        ];

        // Sedes reales del Mundial 2026 (16 ciudades)
        $venues = [
            'Estadio Azteca, Ciudad de México',
            'BMO Field, Toronto',
            'BC Place, Vancouver',
            'SoFi Stadium, Los Ángeles',
            'Mercedes-Benz Stadium, Atlanta',
            'AT&T Stadium, Dallas',
            'NRG Stadium, Houston',
            'Hard Rock Stadium, Miami',
            'MetLife Stadium, Nueva York',
            'Lincoln Financial Field, Filadelfia',
            'Gillette Stadium, Boston',
            'Lumen Field, Seattle',
            "Levi's Stadium, San Francisco",
            'Arrowhead Stadium, Kansas City',
            'Estadio BBVA, Monterrey',
            'Estadio Akron, Guadalajara',
        ];

        // Partidos REALES confirmados de España (grupo H)
        $realFixtures = [
            // [groupKey, idx_home, idx_away, datetime, venue]
            // Partido inaugural: México vs Sudáfrica
            ['A', 0, 1, '2026-06-11 18:00:00', 'Estadio Azteca, Ciudad de México'],
            // España (grupo H): los 3 reales tras sorteo Washington
            ['H', 0, 1, '2026-06-15 18:00:00', 'Mercedes-Benz Stadium, Atlanta'],  // ESP vs CPV
            ['H', 3, 0, '2026-06-26 20:00:00', 'Estadio Akron, Guadalajara'],      // URU vs ESP
            ['H', 0, 2, '2026-06-21 18:00:00', 'Mercedes-Benz Stadium, Atlanta'],  // ESP vs KSA
        ];

        // Marcamos partidos reales como ya generados (en ambas direcciones)
        $reservedKeys = [];
        foreach ($realFixtures as [$g, $h, $a, $dt, $venue]) {
            $reservedKeys["$g-$h-$a"] = true;
            $reservedKeys["$g-$a-$h"] = true; // tambien el invertido para evitar duplicado
            FootballMatch::updateOrCreate(
                [
                    'phase_id' => $groupPhase->id,
                    'home_team' => $groups[$g][$h][1],
                    'away_team' => $groups[$g][$a][1],
                ],
                [
                    'home_code' => $groups[$g][$h][0],
                    'away_code' => $groups[$g][$a][0],
                    'kick_off_at' => $dt,
                    'venue' => $venue,
                    'status' => 'pending',
                ]
            );
        }

        // 6 enfrentamientos por grupo (todas las combinaciones)
        // Generamos los restantes con fechas escalonadas y sedes rotativas
        $combos = [[0, 1], [2, 3], [0, 2], [1, 3], [0, 3], [1, 2]];
        $baseDate = strtotime('2026-06-12 18:00:00');
        $venueIdx = 0;

        foreach ($groups as $g => $teams) {
            foreach ($combos as $cIdx => [$h, $a]) {
                $key = "$g-$h-$a";
                if (isset($reservedKeys[$key])) {
                    continue; // ya creado como real
                }
                // Fecha: cada grupo escalonado por día, jornadas separadas 5 días
                $groupIdx = array_search($g, array_keys($groups));
                $jornada = intval($cIdx / 2); // 0, 0, 1, 1, 2, 2
                $kickoff = $baseDate
                    + ($groupIdx * 86400 / 2)  // medio día por grupo
                    + ($jornada * 5 * 86400)    // 5 días por jornada
                    + ($cIdx % 2 ? 10800 : 0);  // alterna 18:00 / 21:00
                $venue = $venues[$venueIdx % count($venues)];
                $venueIdx++;

                FootballMatch::updateOrCreate(
                    [
                        'phase_id' => $groupPhase->id,
                        'home_team' => $teams[$h][1],
                        'away_team' => $teams[$a][1],
                    ],
                    [
                        'home_code' => $teams[$h][0],
                        'away_code' => $teams[$a][0],
                        'kick_off_at' => date('Y-m-d H:i:s', $kickoff),
                        'venue' => $venue,
                        'status' => 'pending',
                    ]
                );
            }
        }
    }
}
