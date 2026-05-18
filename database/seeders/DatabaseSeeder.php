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
        User::create([
            'name' => 'IIN',
            'email' => 'iinnainggolan03@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
    }
}