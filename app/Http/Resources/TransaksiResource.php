<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
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
            'total_harga' => $this->total_harga,
            'laba' => $this->laba,
            'metode_pembayaran' => $this->metode_pembayaran,
            'status_pembayaran' => $this->status_pembayaran,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'pelanggan' => new PelangganResource($this->whenLoaded('pelanggan')),
            'detail_transaksi' => DetailTransaksiResource::collection($this->whenLoaded('detailTransaksis')),
        ];
    }
}
