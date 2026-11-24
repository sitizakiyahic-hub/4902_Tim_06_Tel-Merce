
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan pengelolaan Tel-Merce')

@section('content')

{{-- SAMBUTAN --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <div>
            <h3>Selamat Datang di Tel-Merce!</h3>
            <p>Tel-U E-Commerce Management System</p>
        </div>
    </div>

    <p>
        Halo, <strong>{{ auth()->user()->name }}</strong>!
        Selamat datang di Dashboard Tel-Merce.
    </p>
</div>

{{-- LAPORAN KHUSUS ADMIN --}}
@if (auth()->user()->role === 'admin')

    {{-- KARTU RINGKASAN --}}
    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 14px;">Laporan Ringkasan Produk</h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">

            <div class="card">
                <p>Total Produk</p>
                <h2 style="color: #4b2995;">
                    {{ number_format($totalProduk, 0, ',', '.') }}
                </h2>
                <small>Jumlah seluruh produk</small>
            </div>

            <div class="card">
                <p>Total Stok</p>
                <h2 style="color: #4b2995;">
                    {{ number_format($totalStok, 0, ',', '.') }}
                </h2>
                <small>Jumlah seluruh persediaan</small>
            </div>

            <div class="card">
                <p>Nilai Inventaris</p>
                <h2 style="color: #4b2995; font-size: 22px;">
                    Rp {{ number_format($nilaiInventaris, 0, ',', '.') }}
                </h2>
                <small>Total nilai harga produk × stok</small>
            </div>

        </div>
    </div>

    {{-- RINGKASAN KATEGORI --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <div>
                <h3>Ringkasan Produk per Kategori</h3>
                <p>Jumlah produk dan nilai persediaan berdasarkan kategori</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Total Stok</th>
                        <th>Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ringkasanKategori as $kategori)
                        <tr>
                            <td>{{ $kategori->kategori }}</td>
                            <td>{{ number_format($kategori->jumlah_produk, 0, ',', '.') }}</td>
                            <td>{{ number_format($kategori->total_stok, 0, ',', '.') }}</td>
                            <td>
                                Rp {{ number_format($kategori->nilai_stok, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PRODUK STOK MENIPIS --}}
    <div class="card">
        <div class="card-header">
            <div>
                <h3>Produk dengan Stok Menipis</h3>
                <p>Daftar produk yang memiliki stok 5 atau kurang</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stokMenipis as $produk)
                        <tr>
                            <td>{{ $produk->sku }}</td>
                            <td>{{ $produk->nama }}</td>
                            <td>{{ $produk->kategori }}</td>
                            <td>
                                <span style="color: #dc2626; font-weight: bold;">
                                    {{ $produk->stok }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                Semua produk memiliki stok lebih dari 5.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

@endsection
