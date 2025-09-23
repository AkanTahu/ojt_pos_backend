<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
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


        Pelanggan::create([
            'user_id' => 1,
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 10',
            'no_hp' => '081234567890',
        ]);

        Pelanggan::create([
            'user_id' => 1,
            'nama' => 'Citra Lestari',
            'alamat' => 'Jl. Pahlawan No. 25',
            'no_hp' => '081234567891',
        ]);

        Pelanggan::create([
            'user_id' => 1,
            'nama' => 'Dewi Anggraini',
            'alamat' => 'Jl. Sudirman Kav. 5',
            'no_hp' => '081234567892',
        ]);

        // Ambil produk yang sudah ada untuk transaksi
        $produk_a = Produk::find(1); // Produk A
        $produk_b = Produk::find(2); // Produk B
        $produk_c = Produk::find(3);

        $transaksi1 = Transaksi::create([
            'user_id' => 1,
            'pelanggan_id' => 1, // Budi Santoso
            'metode_pembayaran' => 'tunai',
            'status_pembayaran' => 'lunas',
        ]);

        // Detail untuk Transaksi 1
        $qty_a1 = 2;
        $qty_b1 = 1;
        DetailTransaksi::create([
            'user_id' => 1,
            'transaksi_id' => $transaksi1->id,
            'produk_id' => $produk_a->id,
            'qty' => $qty_a1,
        ]);
        DetailTransaksi::create([
            'user_id' => 1,
            'transaksi_id' => $transaksi1->id,
            'produk_id' => $produk_b->id,
            'qty' => $qty_b1,
        ]);

        // Update total_harga dan laba untuk Transaksi 1
        $total_harga1 = ($produk_a->harga_jual * $qty_a1) + ($produk_b->harga_jual * $qty_b1);
        $laba1 = (($produk_a->harga_jual - $produk_a->harga_pokok) * $qty_a1) + (($produk_b->harga_jual - $produk_b->harga_pokok) * $qty_b1);
        $transaksi1->update(['total_harga' => $total_harga1, 'laba' => $laba1]);

        // --- Transaksi 2 (Produk B & C) ---
        $transaksi2 = Transaksi::create([
            'user_id' => 1,
            'pelanggan_id' => 2, // Citra Lestari
            'metode_pembayaran' => 'qris',
            'status_pembayaran' => 'lunas',
        ]);

        // Detail untuk Transaksi 2
        $qty_b2 = 3;
        $qty_c2 = 5;
        DetailTransaksi::create(['user_id' => 1, 'transaksi_id' => $transaksi2->id, 'produk_id' => $produk_b->id, 'qty' => $qty_b2]);
        DetailTransaksi::create(['user_id' => 1, 'transaksi_id' => $transaksi2->id, 'produk_id' => $produk_c->id, 'qty' => $qty_c2]);

        // Update total_harga dan laba untuk Transaksi 2
        $total_harga2 = ($produk_b->harga_jual * $qty_b2) + ($produk_c->harga_jual * $qty_c2);
        $laba2 = (($produk_b->harga_jual - $produk_b->harga_pokok) * $qty_b2) + (($produk_c->harga_jual - $produk_c->harga_pokok) * $qty_c2);
        $transaksi2->update(['total_harga' => $total_harga2, 'laba' => $laba2]);
    }
}