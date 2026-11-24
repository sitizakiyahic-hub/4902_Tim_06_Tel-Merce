
@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')
@section('page-subtitle', 'Perbarui informasi produk di katalog Tel-Merce')

@section('content')

<div class="card form-card" style="max-width: 850px;">

    <div class="card-header">
        <div>
            <h3>Form Edit Produk</h3>
            <p>Perbarui informasi produk yang ingin diubah.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('produk.update', $produk->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" name="nama" id="nama"
                   class="form-control"
                   value="{{ old('nama', $produk->nama) }}" required>
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" name="sku" id="sku"
                   class="form-control"
                   value="{{ old('sku', $produk->sku) }}" required>
        </div>

        <div class="form-group">
            <label for="merek">Merek</label>
            <input type="text" name="merek" id="merek"
                   class="form-control"
                   value="{{ old('merek', $produk->merek) }}" required>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                @foreach(['Fashion', 'Elektronik', 'Makanan', 'Kerajinan', 'Lainnya'] as $kategori)
                    <option value="{{ $kategori }}"
                        {{ old('kategori', $produk->kategori) == $kategori ? 'selected' : '' }}>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="berat">Berat (gram)</label>
            <input type="number" name="berat" id="berat"
                   class="form-control"
                   min="0"
                   value="{{ old('berat', $produk->berat) }}" required>
        </div>

        <div class="form-group">
            <label for="warna">Warna</label>
            <input type="text" name="warna" id="warna"
                   class="form-control"
                   value="{{ old('warna', $produk->warna) }}" required>
        </div>

        <div class="form-group">
            <label for="ukuran">Ukuran</label>
            <select name="ukuran" id="ukuran" class="form-control" required>
                @foreach(['S', 'M', 'L', 'XL', '-'] as $ukuran)
                    <option value="{{ $ukuran }}"
                        {{ old('ukuran', $produk->ukuran) == $ukuran ? 'selected' : '' }}>
                        {{ $ukuran }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" name="stok" id="stok"
                   class="form-control"
                   min="0"
                   value="{{ old('stok', $produk->stok) }}" required>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp)</label>
            <input type="number" name="harga" id="harga"
                   class="form-control"
                   min="0" step="0.01"
                   value="{{ old('harga', $produk->harga) }}" required>
        </div>

        <div class="form-group">
            <label for="foto">Foto Produk</label>

            @if($produk->foto)
                <div style="margin-bottom: 12px;">
                    <img src="{{ asset('storage/' . $produk->foto) }}"
                         alt="{{ $produk->nama }}"
                         width="120"
                         style="object-fit: cover; border-radius: 8px;">
                </div>
            @endif

            <input type="file" name="foto" id="foto"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.webp">

            <p class="form-hint">
                Kosongkan jika tidak ingin mengganti foto.
                Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
            </p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection
