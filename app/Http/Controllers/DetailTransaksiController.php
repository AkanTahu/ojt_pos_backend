<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use App\Http\Resources\DetailTransaksiResource;

class DetailTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $details = DetailTransaksi::whereHas('user', function ($query) use ($user) {
            $query->where('toko_id', $user->toko_id);
        })->with(['produk', 'user'])->get();

        return DetailTransaksiResource::collection($details);
    }

    /**
     * Store a newly created resource in storage.
     * WARNING: This method is not recommended. Creating a DetailTransaksi should be
     * part of creating a Transaksi to ensure data integrity (e.g., updating totals).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'produk_id' => 'required|exists:produks,id',
            'qty' => 'required|integer|min:1',
        ]);

        // Basic authorization: check if the parent transaction belongs to the same store.
        $transaksi = Transaksi::find($validated['transaksi_id']);
        if (auth()->user()->toko_id !== $transaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden: Transaksi does not belong to your store.'], 403);
        }

        $detailTransaksi = DetailTransaksi::create($validated + ['user_id' => auth()->id()]);

        // NOTE: This does NOT recalculate Transaksi totals (total_harga, laba).

        return new DetailTransaksiResource($detailTransaksi->load(['produk', 'user']));
    }

    /**
     * Display the specified resource.
     */
    public function show(DetailTransaksi $detailTransaksi)
    {
        if (auth()->user()->toko_id !== $detailTransaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return new DetailTransaksiResource($detailTransaksi->load(['produk', 'user']));
    }

    /**
     * Update the specified resource in storage.
     * WARNING: This method is not recommended as it does not recalculate parent Transaksi totals.
     */
    public function update(Request $request, DetailTransaksi $detailTransaksi)
    {
        if (auth()->user()->toko_id !== $detailTransaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'qty' => 'sometimes|required|integer|min:1',
        ]);

        $detailTransaksi->update($validated);

        // NOTE: This does NOT recalculate Transaksi totals (total_harga, laba).

        return new DetailTransaksiResource($detailTransaksi->load(['produk', 'user']));
    }

    /**
     * Remove the specified resource from storage.
     * WARNING: This method is not recommended as it does not recalculate parent Transaksi totals.
     */
    public function destroy(DetailTransaksi $detailTransaksi)
    {
        if (auth()->user()->toko_id !== $detailTransaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $detailTransaksi->delete();

        // NOTE: This does NOT recalculate Transaksi totals (total_harga, laba).

        return response()->json(['message' => 'Detail Transaksi deleted successfully']);
    }
}
