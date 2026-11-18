<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Ekspedisi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="app-container">

    <div class="topbar">
        <div class="topbar-brand">
            <h2>Direktori Ekspedisi</h2>
            <p>Kelola data kurir, layanan, dan zonasi pengiriman.</p>
        </div>
        <div class="user-profile">
            <span>{{ auth()->user()->name }}</span>
            <span class="user-badge">{{ auth()->user()->role }}</span>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>Daftar Ekspedisi</h3>
            @can('manage-ekspedisi')
                <a href="{{ route('ekspedisi.create') }}" class="btn btn-primary btn-sm">+ Tambah Kurir</a>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Ekspedisi</th>
                        <th>Nomor Telepon</th>
                        <th>Layanan</th>
                        <th>Zonasi</th>
                        @canany(['manage-ekspedisi', 'delete-ekspedisi'])
                            <th style="text-align: right;">Aksi</th>
                        @endcanany
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ekspedisi as $row)
                        <tr>
                            <td><span class="code-tag">{{ $row->kode }}</span></td>
                            <td><strong>{{ $row->nama_ekspedisi }}</strong></td>
                            <td>{{ $row->nomor_telepon }}</td>
                            <td>{{ $row->layanan }}</td>
                            <td>{{ $row->zonasi }}</td>
                            @canany(['manage-ekspedisi', 'delete-ekspedisi'])
                                <td>
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        @can('manage-ekspedisi')
                                            <a href="{{ route('ekspedisi.edit', $row->kode) }}" class="btn btn-secondary btn-sm">Edit</a>
                                        @endcan
                                        @can('delete-ekspedisi')
                                            <form action="{{ route('ekspedisi.destroy', $row->kode) }}" method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger-outline btn-sm">Hapus</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->canany(['manage-ekspedisi', 'delete-ekspedisi']) ? 6 : 5 }}" class="empty-state">
                                <p>Belum ada data ekspedisi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>
