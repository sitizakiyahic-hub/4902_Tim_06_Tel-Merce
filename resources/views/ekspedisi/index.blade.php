
@extends('layouts.app')

@section('title', 'Ekspedisi')
@section('page-title', 'Direktori Ekspedisi')
@section('page-subtitle', 'Informasi kurir dan layanan pengiriman Tel-Merce')

@section('content')

<div class="card">

    <div class="card-header">
        <div>
            <h3>Daftar Ekspedisi</h3>
            <p>Daftar kurir dan layanan pengiriman yang tersedia.</p>
        </div>

        @can('manage-ekspedisi')
            <a href="{{ route('ekspedisi.create') }}" class="btn btn-primary">
                + Tambah Kurir
            </a>
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
                        <td>
                            <span class="code-tag">{{ $row->kode }}</span>
                        </td>

                        <td>
                            <strong>{{ $row->nama_ekspedisi }}</strong>
                        </td>

                        <td>{{ $row->nomor_telepon }}</td>

                        <td>{{ $row->layanan }}</td>

                        <td>{{ $row->zonasi }}</td>

                        @canany(['manage-ekspedisi', 'delete-ekspedisi'])
                            <td>
                                <div class="action-buttons" style="justify-content: flex-end;">

                                    @can('manage-ekspedisi')
                                        <a href="{{ route('ekspedisi.edit', $row->kode) }}"
                                           class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete-ekspedisi')
                                        <form action="{{ route('ekspedisi.destroy', $row->kode) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger-outline btn-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </td>
                        @endcanany
                    </tr>

                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->canany(['manage-ekspedisi', 'delete-ekspedisi']) ? 6 : 5 }}">
                            <div class="empty-state">
                                <p>Belum ada data ekspedisi.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
