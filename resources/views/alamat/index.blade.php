@extends('layouts.app')

@section('title', 'Alamat Pengiriman')
@section('page-title', auth()->user()->role === 'admin' ? 'Data Alamat Pengiriman' : 'Alamat Saya')
@section('page-subtitle', 'Kelola informasi alamat pengiriman Tel-Merce')

@section('content')

<div class="card">

    <div class="card-header">
        <div>
            <h3>
                {{ auth()->user()->role === 'admin'
                    ? 'Semua Alamat Pelanggan'
                    : 'Daftar Alamat Saya' }}
            </h3>

            <p>
                {{ auth()->user()->role === 'admin'
                    ? 'Daftar seluruh alamat pengiriman pelanggan.'
                    : 'Daftar alamat pengiriman yang terdaftar pada akun Anda.' }}
            </p>
        </div>

        <a href="{{ route('alamat.create') }}" class="btn btn-primary">
            + Tambah Alamat
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
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
                            <td>
                                {{ $a->pelanggan->nama ?? '-' }}
                            </td>
                        @endif

                        <td>{{ $a->label }}</td>
                        <td>{{ $a->alamat }}</td>
                        <td>{{ $a->kota }}</td>
                        <td>{{ $a->provinsi }}</td>
                        <td>{{ $a->kode_pos }}</td>
                        <td>{{ $a->status }}</td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('alamat.edit', $a->id) }}"
                                   class="btn btn-secondary btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('alamat.destroy', $a->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus alamat ini?')">
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
                        <td colspan="{{ auth()->user()->role === 'admin' ? 8 : 7 }}">
                            <div class="empty-state">
                                <p>Belum ada alamat pengiriman.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
