<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Armada;
use App\Models\Coordinate;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        =========================================
        USERS
        =========================================
        */

        for ($i = 1; $i <= 50; $i++) {
            User::create([
                'name' => 'Supir ' . $i,
                'email' => 'supir' . $i . '@example.com',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]);
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        /*
        =========================================
        ARMADAS (50 DATA)
        =========================================
        */

        $armadaData = [];

        // 20 TRUCK (3 ukuran)
        $truckSizes = [
            ['Truck Small', 1000],
            ['Truck Medium', 2000],
            ['Truck Large', 3000],
        ];

        for ($i = 1; $i <= 20; $i++) {
            $size = $truckSizes[array_rand($truckSizes)];

            $armadaData[] = [
                'name' => $size[0],
                'capacity' => $size[1],
                'no_plat' => 'L ' . str_pad($i, 3, '0', STR_PAD_LEFT) . ' SBY',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 20 PICKUP
        for ($i = 1; $i <= 20; $i++) {
            $armadaData[] = [
                'name' => 'Pickup',
                'capacity' => 800,
                'no_plat' => 'L ' . str_pad($i, 3, '0', STR_PAD_LEFT) . ' SBY',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 10 CONTAINER (2 ukuran)
        $containerSizes = [
            ['Container 20FT', 5000],
            ['Container 40FT', 10000],
        ];

        for ($i = 1; $i <= 10; $i++) {
            $size = $containerSizes[array_rand($containerSizes)];

            $armadaData[] = [
                'name' => $size[0],
                'capacity' => $size[1],
                'no_plat' => 'L ' . str_pad($i, 3, '0', STR_PAD_LEFT) . ' SBY',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Armada::insert($armadaData);

        /*
        =========================================
        COORDINATES (IMPORT CSV)
        =========================================
        */

        $path = database_path('data/coordinate.csv');

        $rows = array_map('str_getcsv', file($path));
        $header = array_shift($rows);

        $data = [];

        foreach ($rows as $row) {

            $data[] = [
                'area' => $row[0],
                'lat' => $row[1],
                'long' => $row[2],
                'created_at' => now(),
                'updated_at' => now(),
            ];

        }

        Coordinate::insert($data);

        /*
        =========================================
        ORDERS + PIVOT MANDATORIES
        =========================================
        */

        $coordinateIds = Coordinate::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        $armadaIds = Armada::pluck('id')->toArray();

        for ($i = 0; $i < 30; $i++) {
            // Random FROM & TO (tidak boleh sama)
            $from = $coordinateIds[array_rand($coordinateIds)];
            do {
                $to = $coordinateIds[array_rand($coordinateIds)];
            } while ($to === $from);

            $order = Order::create([
                'date' => now()->subDays(rand(0, 30)),
                'from_id' => $from,
                'to_id' => $to,
                'user_id' => $userIds[array_rand($userIds)],
                'armada_id' => $armadaIds[array_rand($armadaIds)],
            ]);

            // Random mandatory (1 - 5 titik, tidak termasuk from & to)
            $availableMandatory = array_diff($coordinateIds, [$from, $to]);

            shuffle($availableMandatory);

            $mandatoryCount = rand(1, min(5, count($availableMandatory)));

            $selectedMandatory = array_slice($availableMandatory, 0, $mandatoryCount);

            $order->mandatories()->sync($selectedMandatory);
        }
    }
}