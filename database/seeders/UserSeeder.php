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
            'name' => 'Toko Contoh',
            'email' => 'seller@test.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        User::create([
            'name' => 'Pembeli Contoh',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);
    }
}
