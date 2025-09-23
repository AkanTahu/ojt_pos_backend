<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Http\Resources\PelangganResource;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $pelanggans = Pelanggan::whereHas('user', function ($query) use ($user) {
            $query->where('toko_id', $user->toko_id);
        })->get();

        return PelangganResource::collection($pelanggans);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'barcode' => 'nullable|string|max:255|unique:pelanggans,barcode',
            'keterangan' => 'nullable|string',
        ]);

        $pelanggan = Pelanggan::create($validated + ['user_id' => auth()->id()]);

        return new PelangganResource($pelanggan);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        if (auth()->user()->toko_id !== $pelanggan->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return new PelangganResource($pelanggan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        if (auth()->user()->toko_id !== $pelanggan->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'barcode' => 'nullable|string|max:255|unique:pelanggans,barcode,' . $pelanggan->id,
            'keterangan' => 'nullable|string',
        ]);

        $pelanggan->update($validated);

        return new PelangganResource($pelanggan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        if (auth()->user()->toko_id !== $pelanggan->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $pelanggan->delete();

        return response()->json(['message' => 'Pelanggan deleted successfully']);
    }
}
