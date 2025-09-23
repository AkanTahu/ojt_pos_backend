<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TransaksiResource;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = auth()->user();
        $transaksis = Transaksi::whereHas('user', function ($query) use ($user) {
            $query->where('toko_id', $user->toko_id);
        })->with(['user', 'pelanggan', 'detailTransaksis.produk'])->latest()->get();

        return TransaksiResource::collection($transaksis);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggans,id',
            'metode_pembayaran' => 'required|string|in:tunai,qris,transfer',
            'status_pembayaran' => 'required|string|in:lunas,belum_lunas',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $totalHarga = 0;
        $totalLaba = 0;

        // Menggunakan DB Transaction untuk memastikan semua query berhasil atau tidak sama sekali
        $transaksi = DB::transaction(function () use ($validated, $user, &$totalHarga, &$totalLaba) {
            // 1. Buat transaksi utama
            $transaksi = Transaksi::create([
                'user_id' => $user->id,
                'pelanggan_id' => $validated['pelanggan_id'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => $validated['status_pembayaran'],
                'total_harga' => 0, // Placeholder, akan diupdate nanti
                'laba' => 0,        // Placeholder, akan diupdate nanti
            ]);

            // 2. Ambil semua produk yang dibutuhkan dalam satu query
            $produkIds = collect($validated['items'])->pluck('produk_id');
            $produks = Produk::find($produkIds)->keyBy('id');

            // 3. Buat detail transaksi dan hitung total
            foreach ($validated['items'] as $item) {
                $produk = $produks[$item['produk_id']];
                $qty = $item['qty'];

                $transaksi->detailTransaksis()->create([
                    'user_id' => $user->id,
                    'produk_id' => $produk->id,
                    'qty' => $qty,
                ]);

                $totalHarga += $produk->harga_jual * $qty;
                $totalLaba += ($produk->harga_jual - $produk->harga_pokok) * $qty;
            }

            // 4. Update transaksi dengan total yang sudah dihitung
            $transaksi->update([
                'total_harga' => $totalHarga,
                'laba' => $totalLaba,
            ]);

            return $transaksi;
        });

        return new TransaksiResource($transaksi->load(['user', 'pelanggan', 'detailTransaksis.produk']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
        if (auth()->user()->toko_id !== $transaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return new TransaksiResource($transaksi->load(['user', 'pelanggan', 'detailTransaksis.produk']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
        if (auth()->user()->toko_id !== $transaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'sometimes|required|string|in:tunai,qris,transfer',
            'status_pembayaran' => 'sometimes|required|string|in:lunas,belum_lunas',
        ]);

        $transaksi->update($validated);

        return new TransaksiResource($transaksi->load(['user', 'pelanggan', 'detailTransaksis.produk']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
        if (auth()->user()->toko_id !== $transaksi->user->toko_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $transaksi->delete(); // Asumsi foreign key di `detail_transaksis` menggunakan onDelete('cascade')

        return response()->json(['message' => 'Transaksi deleted successfully']);
    }
}