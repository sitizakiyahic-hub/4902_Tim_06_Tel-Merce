<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Voucher</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

    <div class="app-container">

        <!-- Topbar / Navigasi Pengguna -->
        <header class="topbar">
            <div class="topbar-brand">
                <h2>Manajemen Voucher</h2>
                <p>Sistem pencatatan dan pengelolaan promo diskon</p>
            </div>

            <div class="user-profile">
                <span>Login sebagai: <strong>{{ auth()->user()->name }}</strong></span>
                <span class="user-badge">{{ auth()->user()->role }}</span>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-danger-outline btn-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </header>

        <!-- Konten Utama: Daftar Voucher -->
        <main class="card">
            <div class="card-header">
                <div>
                    <h3>Daftar Voucher</h3>
                </div>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
                        + Tambah Voucher
                    </a>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Voucher</th>
                            <th>Jenis</th>
                            <th>Nilai Diskon</th>
                            <th>Syarat Minimum</th>
                            <th style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vouchers as $v)
                            <tr>
                                <td>
                                    <span class="code-tag">{{ $v->kode }}</span>
                                </td>
                                <td>{{ $v->jenis }}</td>
                                <td>
                                    {{ $v->jenis == 'Persen (%)'
                                        ? $v->nilai . '%'
                                        : 'Rp ' . number_format($v->nilai, 0, ',', '.') }}
                                </td>
                                <td>
                                    Rp {{ number_format($v->syarat, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if (auth()->user()->role === 'admin')
                                        <div class="action-buttons">
                                            <a href="{{ route('vouchers.edit', $v) }}" class="btn btn-secondary btn-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('vouchers.destroy', $v) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger-outline btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span style="color: var(--text-light);">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <p>Belum ada data voucher yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>

    </div>

</body>
</html>
