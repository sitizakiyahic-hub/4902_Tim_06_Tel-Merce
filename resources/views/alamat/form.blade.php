<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($alamat) ? 'Edit' : 'Tambah' }} Alamat</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 30px; font-family: Arial, sans-serif; background: #f5f2ff; color: #35245c; }
        .container { max-width: 650px; margin: auto; background: white; padding: 30px; border-radius: 14px; }
        h1 { color: #4b2995; }
        label { display: block; margin: 16px 0 7px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #d8ccef; border-radius: 7px; font-family: Arial, sans-serif; }
        textarea { min-height: 90px; resize: vertical; }
        button, a { display: inline-block; padding: 11px 17px; border: none; border-radius: 7px; text-decoration: none; cursor: pointer; margin-top: 20px; }
        button { background: #6d28d9; color: white; }
        a { background: #eee8ff; color: #4b2995; }
        .error { color: #b91c1c; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ isset($alamat) ? 'Edit Alamat Pengiriman' : 'Tambah Alamat Pengiriman' }}</h1>

    <form action="{{ isset($alamat) ? route('alamat.update', $alamat->id) : route('alamat.store') }}" method="POST">
        @csrf
        @if (isset($alamat))
            @method('PUT')
        @endif

        @if ($isAdmin)
            <label>Pelanggan</label>
            <select name="pelanggan_id" required>
                <option value="">Pilih Pelanggan</option>
                @foreach ($pelanggan as $p)
                    <option value="{{ $p->id }}"
                        {{ (string) old('pelanggan_id', $alamat->pelanggan_id ?? '') === (string) $p->id ? 'selected' : '' }}>
                        {{ $p->nama }} - {{ $p->email }}
                    </option>
                @endforeach
            </select>
            @error('pelanggan_id') <div class="error">{{ $message }}</div> @enderror
        @endif

        <label>Label Alamat</label>
        <input type="text" name="label" placeholder="Contoh: Rumah, Kantor"
               value="{{ old('label', $alamat->label ?? '') }}" required>
        @error('label') <div class="error">{{ $message }}</div> @enderror

        <label>Alamat Lengkap</label>
        <textarea name="alamat" required>{{ old('alamat', $alamat->alamat ?? '') }}</textarea>
        @error('alamat') <div class="error">{{ $message }}</div> @enderror

        <label>Kota</label>
        <input type="text" name="kota" value="{{ old('kota', $alamat->kota ?? '') }}" required>
        @error('kota') <div class="error">{{ $message }}</div> @enderror

        <label>Provinsi</label>
        <input type="text" name="provinsi" value="{{ old('provinsi', $alamat->provinsi ?? '') }}" required>
        @error('provinsi') <div class="error">{{ $message }}</div> @enderror

        <label>Kode Pos</label>
        <input type="text" name="kode_pos" value="{{ old('kode_pos', $alamat->kode_pos ?? '') }}" required>
        @error('kode_pos') <div class="error">{{ $message }}</div> @enderror

        <label>Status Alamat</label>
        <select name="status" required>
            <option value="">Pilih Status</option>
            @foreach (['Utama', 'Alternatif'] as $status)
                <option value="{{ $status }}"
                    {{ old('status', $alamat->status ?? '') === $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Simpan</button>
        <a href="{{ route('alamat.index') }}">Kembali</a>
    </form>
</div>
</body>
</html>
