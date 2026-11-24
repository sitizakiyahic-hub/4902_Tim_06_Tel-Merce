
@extends('layouts.app')

@section('title', 'Katalog Produk')
@section('page-title', auth()->user()->role === 'admin' ? 'Manajemen Produk' : 'Katalog Produk')
@section('page-subtitle', 'Temukan berbagai produk menarik di Tel-Merce')

@section('content')

<style>
    .produk-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .produk-card {
        background: white;
        border: 1px solid #eee8ff;
        border-radius: 12px;
        overflow: hidden;
        transition: .2s;
    }

    .produk-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(75, 41, 149, .10);
    }

    .produk-image {
        width: 100%;
        height: 190px;
        object-fit: cover;
        background: #f5f2ff;
    }

    .produk-image-empty {
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f2ff;
        color: #8b819e;
    }

    .produk-info {
        padding: 16px;
    }

    .produk-info h4 {
        margin: 0 0 8px;
        color: #35245c;
    }

    .produk-harga {
        color: #6d28d9;
        font-size: 18px;
        font-weight: bold;
        margin: 12px 0 8px;
    }

    .produk-detail {
        color: #8b819e;
        font-size: 13px;
        margin: 5px 0;
    }

    .produk-stok {
        font-size: 12px;
        color: #4b2995;
        background: #f5f2ff;
        padding: 5px 9px;
        border-radius: 20px;
        display: inline-block;
        margin-top: 5px;
    }
</style>

@if(auth()->user()->role === 'admin')

    {{-- TAMPILAN ADMIN --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h3>Daftar Produk</h3>
                <p>Kelola seluruh produk yang tersedia di Tel-Merce.</p>
            </div>

            <a href="{{ route('produk.create') }}" class="btn btn-primary">
                + Tambah Produk
            </a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Merek</th>
                        <th>Varian</th>
                        <th>Berat</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($produk as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>
                                @if($p->foto)
                                    <img src="{{ asset('storage/' . $p->foto) }}"
                                         alt="{{ $p->nama }}"
                                         width="55"
                                         height="55"
                                         style="object-fit: cover; border-radius: 7px;">
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $p->nama }}</td>
                            <td><span class="code-tag">{{ $p->sku }}</span></td>
                            <td>{{ $p->merek }}</td>
                            <td>{{ $p->warna }} / {{ $p->ukuran }}</td>
                            <td>{{ $p->berat }} gram</td>
                            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td>{{ $p->stok }}</td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('produk.edit', $p->id) }}"
                                       class="btn btn-secondary btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('produk.destroy', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger-outline btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <p>Belum ada produk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@else

    {{-- TAMPILAN PELANGGAN --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h3>Jelajahi Produk</h3>
                <p>Pilih produk yang menarik untuk kamu lihat.</p>
            </div>
        </div>

        @if($produk->count() > 0)

            <div class="produk-grid">

                @foreach($produk as $p)
                    <div class="produk-card">

                        @if($p->foto)
                            <img src="{{ asset('storage/' . $p->foto) }}"
                                 alt="{{ $p->nama }}"
                                 class="produk-image">
                        @else
                            <div class="produk-image-empty">
                                Tidak ada gambar
                            </div>
                        @endif

                        <div class="produk-info">

                            <h4>{{ $p->nama }}</h4>

                            <p class="produk-detail">
                                Merek: {{ $p->merek }}
                            </p>

                            <p class="produk-detail">
                                Varian: {{ $p->warna }} / {{ $p->ukuran }}
                            </p>

                            <p class="produk-detail">
                                Berat: {{ $p->berat }} gram
                            </p>

                            <div class="produk-harga">
                                Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </div>

                            <span class="produk-stok">
                                Stok: {{ $p->stok }}
                            </span>

                        </div>
                    </div>
                @endforeach

            </div>

        @else

            <div class="empty-state">
                <p>Belum ada produk yang tersedia.</p>
            </div>

        @endif

    </div>

@endif

@endsection
