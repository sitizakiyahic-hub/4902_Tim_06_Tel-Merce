@php
    $isEdit = isset($ekspedisi);
@endphp

@extends('layouts.app')

@section('title', $isEdit ? 'Edit Ekspedisi' : 'Tambah Ekspedisi')

@section('page-title', $isEdit ? 'Edit Ekspedisi' : 'Tambah Ekspedisi')

@section('page-subtitle', 'Kelola informasi layanan pengiriman Tel-Merce')

@section('content')

<div class="card form-card" style="max-width: 750px;">

    <div class="card-header">
        <div>
            <h3>{{ $isEdit ? 'Form Edit Kurir' : 'Form Tambah Kurir' }}</h3>
            <p>
                {{ $isEdit
                    ? 'Perbarui informasi layanan ekspedisi.'
                    : 'Masukkan informasi ekspedisi baru.' }}
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('ekspedisi.update', $ekspedisi->kode) : route('ekspedisi.store') }}"
          method="POST">

        @csrf

        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="kode">Kode Kurir</label>

            @if ($isEdit)
                <input type="text"
                       id="kode"
                       class="form-control"
                       value="{{ $ekspedisi->kode }}"
                       disabled>

                <p class="form-hint">Kode kurir tidak dapat diubah.</p>
            @else
                <input type="text"
                       id="kode"
                       name="kode"
                       class="form-control"
                       placeholder="Contoh: JNT"
                       value="{{ old('kode') }}"
                       required
                       autofocus>
            @endif
        </div>

        <div class="form-group">
            <label for="nama_ekspedisi">Nama Ekspedisi</label>
            <input type="text"
                   id="nama_ekspedisi"
                   name="nama_ekspedisi"
                   class="form-control"
                   placeholder="Contoh: J&T Express"
                   value="{{ old('nama_ekspedisi', $ekspedisi->nama_ekspedisi ?? '') }}"
                   required>
        </div>

        <div class="form-group">
            <label for="nomor_telepon">Nomor Telepon</label>
            <input type="text"
                   id="nomor_telepon"
                   name="nomor_telepon"
                   class="form-control"
                   placeholder="Contoh: 08123456789"
                   value="{{ old('nomor_telepon', $ekspedisi->nomor_telepon ?? '') }}"
                   required>
        </div>

        <div class="form-group">
            <label for="layanan">Jenis Layanan</label>

            @php
                $layanan = old('layanan', $ekspedisi->layanan ?? 'Reguler');
            @endphp

            <select id="layanan" name="layanan" class="form-control">
                @foreach (['Reguler', 'Express', 'Kargo'] as $opt)
                    <option value="{{ $opt }}"
                        {{ $layanan == $opt ? 'selected' : '' }}>
                        {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="zonasi">Zonasi</label>
            <input type="text"
                   id="zonasi"
                   name="zonasi"
                   class="form-control"
                   placeholder="Contoh: Nasional"
                   value="{{ old('zonasi', $ekspedisi->zonasi ?? '') }}"
                   required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}
            </button>

            <a href="{{ route('ekspedisi.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection
