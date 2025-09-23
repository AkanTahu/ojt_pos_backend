<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Produk;
use App\Models\Transaksi;
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

        Toko::create([
            'nama' => 'Toko 2 Makmur',
            'alamat' => 'Jalan Selamat 2 Jaya',
            'telepon' => '08222',
            'kota' => 'batu Jaya',
        ]);

        User::factory()->create([
            'name' => 'Admin Toko 1 Jaya',
            'email' => 'asd@example.com',
            'password' => Hash::make('asdasd'),
            'toko_id' => 1
        ]);

        User::factory()->create([
            'name' => 'Admin Toko 2 JayaMakmur',
            'email' => 'asd2@example.com',
            'password' => Hash::make('asdasd'),
            'toko_id' => 2
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

        Transaksi::create([]);



    }
}
