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
        // gabungkan semua titik
        $locations = [
            [$start['lng'], $start['lat']],
        ];

        foreach ($points as $p) {
            $locations[] = [$p['lng'], $p['lat']];
        }

        $locations[] = [$end['lng'], $end['lat']];

        // ambil matrix
        $matrix = $this->getMatrix($locations);

        // index mapping
        $startIndex = 0;
        $endIndex = count($locations) - 1;
        $pointIndexes = range(1, $endIndex - 1);

        // solve TSP sederhana
        $routeIndexes = $this->nearestNeighbor(
            $startIndex,
            $pointIndexes,
            $endIndex,
            $matrix
        );

        // convert ke object
        $allPoints = [
            [
                'lat' => $start['lat'],
                'lng' => $start['lng'],
                'label' => $start['label'] ?? 'Start'
            ],

            ...array_map(function ($p, $i) {
                return [
                    'lat' => $p['lat'],
                    'lng' => $p['lng'],
                    'label' => $p['label'] ?? 'Point ' . ($i + 1)
                ];
            }, $points, array_keys($points)),

            [
                'lat' => $end['lat'],
                'lng' => $end['lng'],
                'label' => $end['label'] ?? 'Destination'
            ]
        ];

        return array_map(fn($i) => $allPoints[$i], $routeIndexes);
    }

    protected function getMatrix(array $locations): array
    {
        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.openrouteservice.org/v2/matrix/driving-car', [
            'locations' => $locations,
            'metrics' => ['duration']
        ]);

        if (!$response->successful()) {
            dd($response->body());
        }

        return $response->json()['durations'];
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