<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laporan Ringkasan Stok - UMKM Toko Online</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container">
  <div class="toolbar">
    <h2>Laporan Ringkasan Stok</h2>
    <div class="toolbar-actions">
      @can('export-produk')
        <a href="{{ route('laporan.export') }}" class="btn">Export CSV</a>
      @endcan
      <a href="{{ route('produk.index') }}" class="btn-secondary">Kembali ke Katalog</a>
    </div>
  </div>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-label">Total Produk</div>
      <div class="stat-value">{{ $totalProduk }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Total Unit Stok</div>
      <div class="stat-value">{{ $totalStok }}</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Estimasi Nilai Inventaris</div>
      <div class="stat-value">Rp {{ number_format($nilaiInventaris, 0, ',', '.') }}</div>
    </div>
  </div>

  <h3 style="margin-bottom: 12px;">Ringkasan per Kategori</h3>
  <div class="table-wrap" style="margin-bottom: 24px;">
    <table>
      <tr>
        <th>Kategori</th>
        <th class="tengah">Jumlah Produk</th>
        <th class="tengah">Total Stok</th>
        <th>Nilai Stok</th>
      </tr>
      @forelse ($perKategori as $row)
        <tr>
          <td>{{ $row['kategori'] }}</td>
          <td class="tengah">{{ $row['jumlah_produk'] }}</td>
          <td class="tengah">{{ $row['total_stok'] }}</td>
          <td class="harga">Rp {{ number_format($row['nilai'], 0, ',', '.') }}</td>
        </tr>
      @empty
        <tr><td colspan="4">Belum ada data produk.</td></tr>
      @endforelse
    </table>
  </div>

  <h3 style="margin-bottom: 12px;">Produk dengan Stok Menipis (&le; 5)</h3>
  <div class="table-wrap">
    <table>
      <tr>
        <th>SKU</th>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th class="tengah">Stok</th>
        <th>Pemilik</th>
      </tr>
      @forelse ($stokMenipis as $item)
        <tr>
          <td>{{ $item->sku }}</td>
          <td>{{ $item->nama }}</td>
          <td>{{ $item->kategori }}</td>
          <td class="tengah">{{ $item->stok }}</td>
          <td>{{ $item->pemilik?->name ?? 'Sistem' }}</td>
        </tr>
      @empty
        <tr><td colspan="5">Semua stok produk aman.</td></tr>
      @endforelse
    </table>
  </div>
</div>

</body>
</html>
