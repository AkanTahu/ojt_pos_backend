<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdukResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'harga_pokok' => $this->harga_pokok,
            'harga_jual' => $this->harga_jual,
            'stok' => $this->stok,
            'is_produk_stok' => $this->is_produk_stok,
            'is_ganti_stok' => $this->is_ganti_stok,
            'gambar' => $this->gambar,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
