@php
  $bisaKelola = auth()->check() && in_array(auth()->user()->role, [\App\Enums\UserRole::Admin, \App\Enums\UserRole::Staff], true);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar Produk</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container">
  <div class="toolbar">
    <h2>Daftar Produk</h2>
    <div class="toolbar-actions">
      @can('export-produk')
        <a href="{{ route('laporan.export') }}" class="btn-secondary">Export CSV</a>
      @endcan
      @can('create', \App\Models\Produk::class)
        <a href="{{ route('produk.form') }}" class="btn">+ Tambah Produk</a>
      @endcan
    </div>
  </div>

  @if (session('pesan'))
    <div class="alert {{ session('pesan_tipe') === 'hapus' ? 'alert-error' : 'alert-sukses' }}">{{ session('pesan') }}</div>
  @endif

  <div class="table-wrap">
    <table>
      <tr>
        <th class="tengah">No</th>
        <th>Gambar Produk</th>
        <th>Nama Produk</th>
        @if ($bisaKelola)
          <th>SKU</th>
        @endif
        <th>Merek</th>
        <th>Varian</th>
        <th>Berat</th>
        <th>Harga</th>
        <th class="tengah">Stok</th>
        @if ($bisaKelola)
          <th>Pemilik / Status</th>
        @endif
        <th class="tengah">Aksi</th>
      </tr>

      @if ($produk->isEmpty())
        <tr><td colspan="{{ $bisaKelola ? 11 : 9 }}">Belum ada produk.</td></tr>
      @endif

      @foreach ($produk as $i => $p)
      <tr>
        <td class="tengah">{{ $i + 1 }}</td>
        <td>
          @if ($p->foto)
            <img src="{{ asset('uploads/' . $p->foto) }}" alt="{{ $p->nama }}">
          @else
            -
          @endif
        </td>
        <td>{{ $p->nama }}</td>
        @if ($bisaKelola)
          <td>{{ $p->sku }}</td>
        @endif
        <td>{{ $p->merek }}</td>
        <td>{{ $p->warna }} / {{ $p->ukuran }}</td>
        <td>{{ $p->berat }} gram</td>
        <td class="harga">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
        <td class="tengah">{{ $p->stok }}</td>
        @if ($bisaKelola)
          <td>
            <div>{{ $p->pemilik?->name ?? 'Sistem' }}</div>
            @if ($p->is_locked)
              <span class="badge badge-warn">Terkunci</span>
            @else
              <span class="badge badge-ok">Terbuka</span>
            @endif
          </td>
        @endif
        <td class="tengah">
          <div class="aksi">
            <a href="{{ route('produk.show', $p) }}" class="btn-detail">Detail</a>
            @can('update', $p)
              <a href="{{ route('produk.form', ['id' => (int) $p->id]) }}" class="btn-edit">Edit</a>
            @endcan
            @can('toggleLock', $p)
              <form action="{{ route('produk.toggle-lock', $p) }}" method="post" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-warn">{{ $p->is_locked ? 'Buka' : 'Kunci' }}</button>
              </form>
            @endcan
            @can('delete', $p)
              <form action="{{ route('produk.hapus') }}" method="post" class="inline"
                    data-confirm="Hapus produk ini?">
                @csrf
                <input type="hidden" name="id" value="{{ (int) $p->id }}">
                <button type="submit" class="btn-hapus">Hapus</button>
              </form>
            @endcan
          </div>
        </td>
      </tr>
      @endforeach
    </table>
  </div>
</div>

</body>
</html>