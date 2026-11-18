<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f5f2ff;
            color: #35245c;
        }
        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 18px #4b29951a;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }
        h1 { color: #4b2995; margin: 0 0 8px; }
        .subtitle { color: #8b819e; margin: 0; }
        .nav { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 25px; }
        a, button {
            display: inline-block;
            border: none;
            border-radius: 7px;
            padding: 10px 15px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }
        .nav a, .secondary { background: #eee8ff; color: #4b2995; }
        .primary { background: #6d28d9; color: white; }
        .edit { background: #eee8ff; color: #4b2995; }
        .delete { background: #fee2e2; color: #b91c1c; }
        .logout { background: #eee8ff; color: #4b2995; }
        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 7px;
            margin-bottom: 18px;
        }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee8ff;
        }
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
            <h1>Data Pelanggan</h1>
            <p class="subtitle">Kelola informasi pelanggan toko.</p>
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
        <div>
            <strong>
                {{ auth()->user()->role === 'admin' ? 'Semua Data Pelanggan' : 'Profil Saya' }}
            </strong>
        </div>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('pelanggan.create') }}" class="primary">+ Tambah Pelanggan</a>
        @endif
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Member</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggan as $p)
                    <tr>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>{{ $p->member }}</td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('pelanggan.edit', $p->id) }}" class="edit">Edit</a>

                                @if (auth()->user()->role === 'admin')
                                    <form action="{{ route('pelanggan.destroy', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">Belum ada data pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
