
@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')
@section('page-subtitle', 'Masukkan informasi produk baru ke katalog Tel-Merce')

@section('content')

<div class="card form-card" style="max-width: 850px;">

    <div class="card-header">
        <div>
            <h3>Form Tambah Produk</h3>
            <p>Lengkapi seluruh informasi produk di bawah ini.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('produk.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="form-group">
            <label for="nama">Nama Produk</label>
            <input type="text" name="nama" id="nama"
                   class="form-control"
                   value="{{ old('nama') }}" required>
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" name="sku" id="sku"
                   class="form-control"
                   value="{{ old('sku') }}" required>
        </div>

        <div class="form-group">
            <label for="merek">Merek</label>
            <input type="text" name="merek" id="merek"
                   class="form-control"
                   value="{{ old('merek') }}" required>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select name="kategori" id="kategori" class="form-control" required>
                <option value="">Pilih Kategori</option>

                @foreach(['Fashion', 'Elektronik', 'Makanan', 'Kerajinan', 'Lainnya'] as $kategori)
                    <option value="{{ $kategori }}"
                        {{ old('kategori') == $kategori ? 'selected' : '' }}>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="berat">Berat (gram)</label>
            <input type="number" name="berat" id="berat"
                   class="form-control"
                   min="0" value="{{ old('berat') }}" required>
        </div>

        <div class="form-group">
            <label for="warna">Warna</label>
            <input type="text" name="warna" id="warna"
                   class="form-control"
                   value="{{ old('warna') }}" required>
        </div>

        <div class="form-group">
            <label for="ukuran">Ukuran</label>
            <select name="ukuran" id="ukuran" class="form-control" required>
                <option value="">Pilih Ukuran</option>

                @foreach(['S', 'M', 'L', 'XL', '-'] as $ukuran)
                    <option value="{{ $ukuran }}"
                        {{ old('ukuran') == $ukuran ? 'selected' : '' }}>
                        {{ $ukuran }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" name="stok" id="stok"
                   class="form-control"
                   min="0" value="{{ old('stok') }}" required>
        </div>

        <div class="form-group">
            <label for="harga">Harga (Rp)</label>
            <input type="number" name="harga" id="harga"
                   class="form-control"
                   min="0" step="0.01"
                   value="{{ old('harga') }}" required>
        </div>

        <div class="form-group">
            <label for="foto">Foto Produk</label>
            <input type="file" name="foto" id="foto"
                   class="form-control"
                   accept=".jpg,.jpeg,.png,.webp">

            <p class="form-hint">
                Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
            </p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Simpan Produk
            </button>

            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection
