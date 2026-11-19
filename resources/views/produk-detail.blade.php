<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Detail Produk - {{ $produk->nama }}</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container">
  <div class="toolbar">
    <h2>Detail Produk</h2>
    <div class="toolbar-actions">
      @can('update', $produk)
        <a href="{{ route('produk.edit', ['id' => (int) $produk->id]) }}" class="btn-edit">Edit Produk</a>
      @endcan
      <a href="{{ route('produk.index') }}" class="btn-secondary">Kembali ke Katalog</a>
    </div>
  </div>

  <div class="detail-grid">
    <div>
      @if ($produk->foto)
        <img src="{{ asset('uploads/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="detail-foto">
      @else
        <div class="info-box" style="margin-top: 0; text-align: center;">Tidak ada foto produk</div>
      @endif
    </div>

    <div class="table-wrap">
      <table>
        <tr><th>Nama Produk</th><td>{{ $produk->nama }}</td></tr>
        <tr><th>Merek</th><td>{{ $produk->merek }}</td></tr>
        <tr><th>Kategori</th><td>{{ $produk->kategori }}</td></tr>
        <tr><th>Varian</th><td>{{ $produk->warna }} / {{ $produk->ukuran }}</td></tr>
        <tr><th>Berat</th><td>{{ $produk->berat }} gram</td></tr>
        <tr><th>Stok</th><td>{{ $produk->stok }}</td></tr>
        <tr><th>Harga</th><td class="harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td></tr>
        @if (auth()->check() && in_array(auth()->user()->role, [\App\Enums\UserRole::Admin, \App\Enums\UserRole::Staff], true))
        <tr><th>SKU</th><td>{{ $produk->sku }}</td></tr>  
        <tr><th>Dikelola Oleh</th><td>{{ $produk->pemilik?->name ?? 'Sistem' }}</td></tr>
          <tr>
            <th>Status Edit</th>
            <td>
              @if ($produk->is_locked)
                <span class="badge badge-warn">Terkunci oleh Admin</span>
              @else
                <span class="badge badge-ok">Terbuka</span>
              @endif
            </td>
          </tr>
        @endif
      </table>
    </div>
  </div>
</div>

</body>
</html>