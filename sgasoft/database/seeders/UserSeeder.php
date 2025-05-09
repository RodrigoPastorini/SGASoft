<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@teste.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'status' => true,
        ]);

        User::create([
            'name' => 'Vendedor User',
            'email' => 'vendedor@teste.com',
            'password' => Hash::make('12345678'),
            'role' => 'seller',
            'status' => true,
        ]);
    }
}
