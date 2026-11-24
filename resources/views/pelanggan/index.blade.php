
@extends('layouts.app')

@section('title', 'Pelanggan')
@section('page-title', auth()->user()->role === 'admin' ? 'Data Pelanggan' : 'Profil Saya')
@section('page-subtitle', 'Kelola informasi pelanggan Tel-Merce')

@section('content')

<div class="card">

    <div class="card-header">
        <div>
            <h3>
                {{ auth()->user()->role === 'admin' ? 'Semua Data Pelanggan' : 'Informasi Profil Saya' }}
            </h3>
            <p>
                {{ auth()->user()->role === 'admin'
                    ? 'Daftar seluruh pelanggan yang terdaftar di Tel-Merce.'
                    : 'Informasi akun pelanggan Anda.' }}
            </p>
        </div>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
                + Tambah Pelanggan
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table">
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
                @forelse($pelanggan as $p)
                    <tr>
                        <td><strong>{{ $p->nama }}</strong></td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->no_hp }}</td>
                        <td>{{ $p->member }}</td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('pelanggan.edit', $p->id) }}"
                                   class="btn btn-secondary btn-sm">
                                    Edit
                                </a>

                                @if(auth()->user()->role === 'admin')
                                    <form action="{{ route('pelanggan.destroy', $p->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger-outline btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <p>Belum ada data pelanggan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
