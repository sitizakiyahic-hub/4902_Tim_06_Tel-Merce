<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($pelanggan) ? 'Edit' : 'Tambah' }} Pelanggan</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 30px; font-family: Arial, sans-serif; background: #f5f2ff; color: #35245c; }
        .container { max-width: 650px; margin: auto; background: white; padding: 30px; border-radius: 14px; }
        h1 { color: #4b2995; }
        label { display: block; margin: 16px 0 7px; font-weight: bold; }
        input, select { width: 100%; padding: 12px; border: 1px solid #d8ccef; border-radius: 7px; }
        button, a { display: inline-block; padding: 11px 17px; border: none; border-radius: 7px; text-decoration: none; cursor: pointer; margin-top: 20px; }
        button { background: #6d28d9; color: white; }
        a { background: #eee8ff; color: #4b2995; }
        .error { color: #b91c1c; font-size: 13px; }
        .readonly { background: #f5f2ff; }
    </style>
</head>
<body>
<div class="container">
    <h1>{{ isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}</h1>

    <form action="{{ isset($pelanggan) ? route('pelanggan.update', $pelanggan->id) : route('pelanggan.store') }}" method="POST">
        @csrf
        @if (isset($pelanggan))
            @method('PUT')
        @endif

        <label>Nama Pelanggan</label>
        <input type="text" name="nama" value="{{ old('nama', $pelanggan->nama ?? '') }}" required>
        @error('nama') <div class="error">{{ $message }}</div> @enderror

        <label>Email</label>
        @if (auth()->user()->role === 'admin')
            <input type="email" name="email" value="{{ old('email', $pelanggan->email ?? '') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror
        @else
            <input type="email" value="{{ $pelanggan->email }}" class="readonly" readonly>
        @endif

        <label>Nomor HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}" required>
        @error('no_hp') <div class="error">{{ $message }}</div> @enderror

        <label>Jenis Member</label>
        <select name="member" required>
            <option value="">Pilih Member</option>
            @foreach (['Gold', 'Platinum', 'Silver'] as $jenis)
                <option value="{{ $jenis }}" {{ old('member', $pelanggan->member ?? '') === $jenis ? 'selected' : '' }}>
                    {{ $jenis }}
                </option>
            @endforeach
        </select>
        @error('member') <div class="error">{{ $message }}</div> @enderror

        <button type="submit">Simpan</button>
        <a href="{{ route('pelanggan.index') }}">Kembali</a>
    </form>
</div>
</body>
</html>
