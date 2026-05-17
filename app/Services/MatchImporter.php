<?php

namespace App\Services;

use App\Models\FootballMatch;
use App\Models\TournamentPhase;
use Illuminate\Http\UploadedFile;

class MatchImporter
{
    public function fromUploadedFile(UploadedFile $file): array
    {
        $contents = file_get_contents($file->getRealPath());
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'json' || str_starts_with(trim($contents), '[') || str_starts_with(trim($contents), '{')) {
            $rows = json_decode($contents, true);
            if (!is_array($rows)) {
                throw new \RuntimeException('JSON inválido');
            }
            // Permitir tanto {matches: [...]} como [...]
            if (isset($rows['matches']) && is_array($rows['matches'])) {
                $rows = $rows['matches'];
            }
            return $this->fromArray($rows);
        }

        // CSV: phase_code,home_team,away_team,kick_off_at,venue,home_score,away_score
        $rows = [];
        $lines = preg_split('/\R/', $contents);
        $header = null;
        foreach ($lines as $line) {
            if (trim($line) === '') {
                continue;
            }
            $fields = str_getcsv($line);
            if ($header === null) {
                $header = array_map(fn ($h) => trim(strtolower($h)), $fields);
                continue;
            }
            $row = array_combine($header, $fields);
            $rows[] = $row;
        }

        return $this->fromArray($rows);
    }

    public function fromArray(array $rows): array
    {
        $created = [];
        $updated = [];
        $skipped = [];

        foreach ($rows as $row) {
            $phase = TournamentPhase::where('code', $row['phase_code'] ?? null)->first();
            if (!$phase) {
                $skipped[] = ['row' => $row, 'reason' => 'Fase no encontrada'];
                continue;
            }

            $match = FootballMatch::updateOrCreate(
                [
                    'phase_id' => $phase->id,
                    'home_team' => $row['home_team'],
                    'away_team' => $row['away_team'],
                    'kick_off_at' => $row['kick_off_at'],
                ],
                [
                    'home_code' => $row['home_code'] ?? null,
                    'away_code' => $row['away_code'] ?? null,
                    'venue' => $row['venue'] ?? null,
                    'home_score' => $this->cleanScore($row['home_score'] ?? null),
                    'away_score' => $this->cleanScore($row['away_score'] ?? null),
                    'status' => $this->statusFromScores($row['home_score'] ?? null, $row['away_score'] ?? null),
                ]
            );

            if ($match->wasRecentlyCreated) {
                $created[] = $match;
            } else {
                $updated[] = $match;
            }
        }

        return [
            'created' => count($created),
            'updated' => count($updated),
            'skipped' => $skipped,
        ];
    }

    private function cleanScore(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === 'null') {
            return null;
        }
        return (int) $value;
    }

    private function statusFromScores(mixed $home, mixed $away): string
    {
        if ($this->cleanScore($home) === null || $this->cleanScore($away) === null) {
            return 'pending';
        }
        return 'played';
    }
}
