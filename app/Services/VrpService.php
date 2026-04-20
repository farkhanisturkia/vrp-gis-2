<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VrpService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.ors.key');
    }

    public function solve(array $start, array $points, array $end): array
    {
        $locations = [
            [$start['lng'], $start['lat']],
        ];

        foreach ($points as $p) {
            $locations[] = [$p['lng'], $p['lat']];
        }

        $locations[] = [$end['lng'], $end['lat']];

        // ================= MATRIX =================
        $matrix = $this->getMatrix($locations);

        $distanceMatrix = $matrix['distance'];
        $timeMatrix = $matrix['duration'];

        $startIndex = 0;
        $endIndex = count($locations) - 1;
        $pointIndexes = range(1, $endIndex - 1);

        // ================= VRP =================
        $steps = [];
        $routeIndexes = [$startIndex];
        $current = $startIndex;
        $unvisited = $pointIndexes;

        while (count($unvisited)) {
            $next = collect($unvisited)
                ->sortBy(fn($i) => $timeMatrix[$current][$i])
                ->first();

            $steps[] = "Dari titik {$current} pilih {$next} (waktu: " .
                round($timeMatrix[$current][$next] / 60, 2) . " menit)";

            $routeIndexes[] = $next;
            $current = $next;

            $unvisited = array_values(array_filter($unvisited, fn($i) => $i !== $next));
        }

        $routeIndexes[] = $endIndex;

        // ================= BUILD POINTS =================
        $allPoints = [
            [
                'id' => 'A',
                'lat' => $start['lat'],
                'lng' => $start['lng'],
                'label' => $start['label'] ?? 'Start'
            ],
            ...array_map(function ($p, $i) {
                return [
                    'id' => 'P' . ($i + 1),
                    'lat' => $p['lat'],
                    'lng' => $p['lng'],
                    'label' => $p['label'] ?? 'Point ' . ($i + 1)
                ];
            }, $points, array_keys($points)),
            [
                'id' => 'Z',
                'lat' => $end['lat'],
                'lng' => $end['lng'],
                'label' => $end['label'] ?? 'Destination'
            ]
        ];

        // ================= TOTAL =================
        $totalDistance = 0;
        $totalTime = 0;

        for ($i = 0; $i < count($routeIndexes) - 1; $i++) {
            $a = $routeIndexes[$i];
            $b = $routeIndexes[$i + 1];

            $totalDistance += $distanceMatrix[$a][$b];
            $totalTime += $timeMatrix[$a][$b];
        }

        return [
            'points' => $allPoints,
            'distance_matrix' => $distanceMatrix,
            'time_matrix' => $timeMatrix,
            'steps' => $steps,
            'route' => array_map(fn($i) => $allPoints[$i], $routeIndexes),
            'total_distance' => round($totalDistance / 1000, 2), // km
            'total_time' => round($totalTime / 60, 2) // menit
        ];
    } 

    protected function getMatrix(array $locations): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.openrouteservice.org/v2/matrix/driving-car', [
            'locations' => $locations,
            'metrics' => ['distance', 'duration']
        ]);

        if (!$response->successful()) {
            dd($response->body());
        }

        return [
            'distance' => $response->json()['distances'], // meter
            'duration' => $response->json()['durations']  // detik
        ];
    }

    protected function nearestNeighbor(int $start, array $points, int $end, array $matrix): array
    {
        $route = [$start];
        $current = $start;
        $unvisited = $points;

        while (count($unvisited)) {
            $next = collect($unvisited)
                ->sortBy(fn($i) => $matrix[$current][$i])
                ->first();

            $route[] = $next;
            $current = $next;

            $unvisited = array_values(array_filter($unvisited, fn($i) => $i !== $next));
        }

        $route[] = $end;

        return $route;
    }
}