<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung ringkasan produk
        $totalProduk = Produk::count();

        $totalStok = Produk::sum('stok');

        $nilaiInventaris = Produk::selectRaw(
            'COALESCE(SUM(harga * stok), 0) as total'
        )->value('total');

        // Menghitung ringkasan berdasarkan kategori
        $ringkasanKategori = Produk::select(
                'kategori',
                DB::raw('COUNT(*) as jumlah_produk'),
                DB::raw('SUM(stok) as total_stok'),
                DB::raw('SUM(harga * stok) as nilai_stok')
            )
            ->groupBy('kategori')
            ->orderBy('kategori')
            ->get();

        // Mengambil produk dengan stok 5 atau kurang
        $stokMenipis = Produk::where('stok', '<=', 5)
            ->orderBy('stok')
            ->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'nilaiInventaris',
            'ringkasanKategori',
            'stokMenipis'
        ));
    }
}
