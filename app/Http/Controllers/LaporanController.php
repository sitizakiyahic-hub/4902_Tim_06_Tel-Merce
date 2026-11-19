<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function stok(): View
    {
        Gate::authorize('view-stock-report');

        $produk = Produk::with('pemilik')->orderBy('stok')->get();

        $totalProduk = $produk->count();
        $totalStok = (int) $produk->sum('stok');
        $nilaiInventaris = (int) $produk->sum(fn (Produk $item): int => $item->stok * $item->harga);
        $stokMenipis = $produk->where('stok', '<=', 5)->values();

        $perKategori = $produk->groupBy('kategori')->map(function ($items, string $kategori): array {
            return [
                'kategori' => $kategori,
                'jumlah_produk' => $items->count(),
                'total_stok' => (int) $items->sum('stok'),
                'nilai' => (int) $items->sum(fn (Produk $item): int => $item->stok * $item->harga),
            ];
        })->values();

        return view('laporan.stok', [
            'totalProduk' => $totalProduk,
            'totalStok' => $totalStok,
            'nilaiInventaris' => $nilaiInventaris,
            'stokMenipis' => $stokMenipis,
            'perKategori' => $perKategori,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        Gate::authorize('export-produk');

        $produk = Produk::with('pemilik')->orderByDesc('id')->get();

        return response()->streamDownload(function () use ($produk): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, [
                'ID',
                'SKU',
                'Nama Produk',
                'Merek',
                'Kategori',
                'Berat (gram)',
                'Warna',
                'Ukuran',
                'Stok',
                'Harga (Rp)',
                'Pemilik',
                'Status Kunci',
            ]);

            foreach ($produk as $item) {
                fputcsv($handle, [
                    $item->id,
                    $this->sanitizeCsvCell($item->sku),
                    $this->sanitizeCsvCell($item->nama),
                    $this->sanitizeCsvCell($item->merek),
                    $this->sanitizeCsvCell($item->kategori),
                    $item->berat,
                    $this->sanitizeCsvCell($item->warna),
                    $this->sanitizeCsvCell($item->ukuran),
                    $item->stok,
                    $item->harga,
                    $this->sanitizeCsvCell($item->pemilik?->name ?? 'Sistem'),
                    $item->is_locked ? 'Terkunci' : 'Terbuka',
                ]);
            }

            fclose($handle);
        }, 'katalog-produk.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function sanitizeCsvCell(string $value): string
    {
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
