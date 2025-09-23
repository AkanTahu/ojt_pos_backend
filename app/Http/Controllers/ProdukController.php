<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Resources\ProdukResource;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $userIds = User::where('toko_id', $user->toko_id)->pluck('id');
        $produks = Produk::whereIn('user_id', $userIds)->get();

        return ProdukResource::collection($produks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga_pokok' => 'required|integer',
            'harga_jual' => 'required|integer',
            'stok' => 'required|string',
            'is_produk_stok' => 'required|boolean',
            'is_ganti_stok' => 'required|boolean',
            'gambar' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $produk = Produk::create($validated + ['user_id' => auth()->id()]);

        return new ProdukResource($produk);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return new ProdukResource($produk);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'harga_pokok' => 'sometimes|required|integer',
            'harga_jual' => 'sometimes|required|integer',
            'stok' => 'sometimes|required|string',
            'is_produk_stok' => 'sometimes|required|boolean',
            'is_ganti_stok' => 'sometimes|required|boolean',
            'gambar' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $produk->update($validated);

        return new ProdukResource($produk);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return response()->json(['message' => 'Produk deleted']);
    }
}