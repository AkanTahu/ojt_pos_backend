<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTransaksiResource extends JsonResource
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
            'qty' => $this->qty,
            'user' => new UserResource($this->whenLoaded('user')),
            'produk' => new ProdukResource($this->whenLoaded('produk')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
