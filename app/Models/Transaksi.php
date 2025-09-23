<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Nama relasi 'detailTransaksis' sesuai dengan yang digunakan di TransaksiResource
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
