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

        $order1 = Order::create([
            'date' => now(),
            'from_id' => 1,
            'to_id' => 2,
            'user_id' => 1,
            'armada_id' => 1,
        ]);

        $order1->mandatories()->sync([3]);

        $order2 = Order::create([
            'date' => now(),
            'from_id' => 2,
            'to_id' => 4,
            'user_id' => 2,
            'armada_id' => 2,
        ]);

        $order2->mandatories()->sync([1, 3]);

        $order3 = Order::create([
            'date' => now(),
            'from_id' => 3,
            'to_id' => 1,
            'user_id' => 3,
            'armada_id' => 3,
        ]);

        $order3->mandatories()->sync([1, 2, 4]);
    }
}