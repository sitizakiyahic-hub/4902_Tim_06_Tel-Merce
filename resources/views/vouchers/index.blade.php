@extends('layouts.app')

@section('title', 'Voucher')
@section('page-title', 'Manajemen Voucher')
@section('page-subtitle', 'Kelola promo dan potongan harga Tel-Merce')

@section('content')

<div class="card">

    <div class="card-header">
        <div>
            <h3>Daftar Voucher</h3>
            <p>Data voucher promo yang tersedia di Tel-Merce.</p>
        </div>

        @if(auth()->user()->role === 'admin')
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
                            @if(auth()->user()->role === 'admin')
                                <div class="action-buttons">
                                    <a href="{{ route('vouchers.edit', $v) }}"
                                       class="btn btn-secondary btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('vouchers.destroy', $v) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger-outline btn-sm">
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

</div>

@endsection
