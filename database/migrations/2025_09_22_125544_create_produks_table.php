<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('nama')->nullable();
            $table->int('harga_pokok')->nullable();
            $table->int('harga_jual')->nullable();
            $table->string('stok')->nullable();
            $table->boolean('is_produk_stok')->nullable();
            $table->boolean('is_ganti_stok')->nullable();
            $table->string('gambar')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
