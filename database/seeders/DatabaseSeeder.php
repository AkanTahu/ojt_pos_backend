<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Produk;
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

        Produk::create([
            'user_id' => 1,
            'nama' => 'Produk A',
            'harga_pokok' => 10000,
            'harga_jual' => 15000,
            'stok' => '50',
            'is_produk_stok' => true,
            'is_ganti_stok' => false,
            'gambar' => null,
            'keterangan' => 'Produk A unggulan',
        ]);

        Produk::create([
            'user_id' => 1,
            'nama' => 'Produk B',
            'harga_pokok' => 20000,
            'harga_jual' => 25000,
            'stok' => '30',
            'is_produk_stok' => true,
            'is_ganti_stok' => false,
            'gambar' => null,
            'keterangan' => 'Produk B best seller',
        ]);

        Produk::create([
            'user_id' => 1,
            'nama' => 'Produk C',
            'harga_pokok' => 5000,
            'harga_jual' => 8000,
            'stok' => '100',
            'is_produk_stok' => true,
            'is_ganti_stok' => true,
            'gambar' => null,
            'keterangan' => 'Produk C murah meriah',
        ]);
    }
}
