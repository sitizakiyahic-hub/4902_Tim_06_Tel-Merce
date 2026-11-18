@php $isEdit = isset($ekspedisi); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEdit ? 'Edit' : 'Tambah' }} Ekspedisi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="app-container">
    <div class="card form-card">
        <div class="card-header">
            <div>
                <h3>{{ $isEdit ? 'Edit' : 'Tambah' }} Kurir</h3>
                <p class="form-hint" style="margin-top: 2px;">{{ $isEdit ? 'Ubah data ekspedisi.' : 'Masukkan data ekspedisi.' }}</p>
            </div>
            <a href="{{ route('ekspedisi.index') }}" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $isEdit ? route('ekspedisi.update', $ekspedisi->kode) : route('ekspedisi.store') }}" method="POST">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="kode">Kode Kurir</label>
                @if ($isEdit)
                    <input type="text" id="kode" class="form-control" value="{{ $ekspedisi->kode }}" disabled>
                    <div class="form-hint">Kode kurir tidak dapat diubah.</div>
                @else
                    <input type="text" id="kode" name="kode" class="form-control" placeholder="Contoh: JNT" value="{{ old('kode') }}" required autofocus>
                @endif
            </div>

            <div class="form-group">
                <label for="nama_ekspedisi">Nama Ekspedisi</label>
                <input type="text" id="nama_ekspedisi" name="nama_ekspedisi" class="form-control" placeholder="Contoh: J&T Express"
                       value="{{ old('nama_ekspedisi', $ekspedisi->nama_ekspedisi ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="nomor_telepon">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control" placeholder="Contoh: 08123456789"
                       value="{{ old('nomor_telepon', $ekspedisi->nomor_telepon ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="layanan">Jenis Layanan</label>
                @php $layanan = old('layanan', $ekspedisi->layanan ?? 'Reguler'); @endphp
                <select id="layanan" name="layanan" class="form-control">
                    @foreach (['Reguler', 'Express', 'Kargo'] as $opt)
                        <option value="{{ $opt }}" {{ $layanan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="zonasi">Zonasi</label>
                <input type="text" id="zonasi" name="zonasi" class="form-control" placeholder="Contoh: Nasional"
                       value="{{ old('zonasi', $ekspedisi->zonasi ?? '') }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}</button>
                <a href="{{ route('ekspedisi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
