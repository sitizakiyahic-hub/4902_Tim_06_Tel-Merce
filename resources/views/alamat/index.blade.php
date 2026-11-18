<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alamat Pengiriman</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 30px; font-family: Arial, sans-serif; background: #f5f2ff; color: #35245c; }
        .container { max-width: 1150px; margin: auto; background: white; padding: 30px; border-radius: 14px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; }
        h1 { color: #4b2995; margin: 0 0 8px; }
        .subtitle { color: #8b819e; }
        .nav { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }
        a, button { display: inline-block; padding: 10px 15px; border: none; border-radius: 7px; text-decoration: none; cursor: pointer; font-size: 14px; }
        .nav a, .secondary { background: #eee8ff; color: #4b2995; }
        .primary { background: #6d28d9; color: white; }
        .edit { background: #eee8ff; color: #4b2995; }
        .delete { background: #fee2e2; color: #b91c1c; }
        .logout { background: #eee8ff; color: #4b2995; }
        .success { padding: 12px; background: #dcfce7; color: #166534; border-radius: 7px; margin-bottom: 18px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 13px; text-align: left; border-bottom: 1px solid #eee8ff; }
        th { background: #f5f2ff; color: #4b2995; }
        .actions { display: flex; gap: 7px; align-items: center; }
        .actions form { margin: 0; }
        .empty { text-align: center; color: #8b819e; padding: 30px; }
    </style>
</head>
<body>
<div class="container">
    <div class="topbar">
        <div>
            <h1>Alamat Pengiriman</h1>
            <p class="subtitle">Kelola alamat pengiriman pelanggan.</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout" type="submit">Logout</button>
        </form>
    </div>

    <div class="nav">
        <a href="{{ route('vouchers.index') }}">Voucher</a>
        <a href="{{ route('pelanggan.index') }}">Pelanggan</a>
        <a href="{{ route('alamat.index') }}">Alamat Pengiriman</a>
    </div>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="topbar">
        <strong>{{ auth()->user()->role === 'admin' ? 'Semua Alamat Pelanggan' : 'Alamat Saya' }}</strong>
        <a href="{{ route('alamat.create') }}" class="primary">+ Tambah Alamat</a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    @if (auth()->user()->role === 'admin')
                        <th>Nama Pelanggan</th>
                    @endif
                    <th>Label</th>
                    <th>Alamat</th>
                    <th>Kota</th>
                    <th>Provinsi</th>
                    <th>Kode Pos</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alamat as $a)
                    <tr>
                        @if (auth()->user()->role === 'admin')
                            <td>{{ $a->pelanggan->nama }}</td>
                        @endif
                        <td>{{ $a->label }}</td>
                        <td>{{ $a->alamat }}</td>
                        <td>{{ $a->kota }}</td>
                        <td>{{ $a->provinsi }}</td>
                        <td>{{ $a->kode_pos }}</td>
                        <td>{{ $a->status }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('alamat.edit', $a->id) }}" class="edit">Edit</a>
                                <form action="{{ route('alamat.destroy', $a->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}" class="empty">
                            Belum ada alamat pengiriman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
