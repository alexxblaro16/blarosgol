<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin FIFA',
            'email' => 'admin@mundial2026.test',
            'password' => Hash::make('admin1234'),
            'is_admin' => true,
        ]);

        User::create([
            'name' => 'Alejandro Blanco',
            'email' => 'alejandro@mundial2026.test',
            'password' => Hash::make('alex1234'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Rodrigo García',
            'email' => 'rodrigo@mundial2026.test',
            'password' => Hash::make('rodri1234'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Manuel Bayo',
            'email' => 'manuel@mundial2026.test',
            'password' => Hash::make('manu1234'),
            'is_admin' => false,
        ]);
    }
}
