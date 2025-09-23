<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Toko::create([
            'nama' => 'Toko 1 Jaya',
            'alamat' => 'Jalan Selamat 1 Jaya',
            'telepon' => '08111',
            'kota' => 'Malang Jaya',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'toko_id' => 1
        ]);
    }
}
