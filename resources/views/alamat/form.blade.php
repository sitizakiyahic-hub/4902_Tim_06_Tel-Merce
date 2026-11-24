
@extends('layouts.app')

@section('title', isset($alamat) ? 'Edit Alamat' : 'Tambah Alamat')

@section('page-title', isset($alamat) ? 'Edit Alamat Pengiriman' : 'Tambah Alamat Pengiriman')

@section('page-subtitle', 'Lengkapi informasi alamat pengiriman Tel-Merce')

@section('content')

<div class="card form-card" style="max-width: 750px;">

    <div class="card-header">
        <div>
            <h3>{{ isset($alamat) ? 'Form Edit Alamat' : 'Form Tambah Alamat' }}</h3>
            <p>Masukkan informasi alamat pengiriman dengan benar.</p>
        </div>
    </div>

    <form action="{{ isset($alamat) ? route('alamat.update', $alamat->id) : route('alamat.store') }}"
          method="POST">

        @csrf

        @if (isset($alamat))
            @method('PUT')
        @endif

        @if ($isAdmin)
            <div class="form-group">
                <label for="pelanggan_id">Pelanggan</label>

                <select name="pelanggan_id" id="pelanggan_id" class="form-control" required>
                    <option value="">Pilih Pelanggan</option>

                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id }}"
                            {{ (string) old('pelanggan_id', $alamat->pelanggan_id ?? '') === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} - {{ $p->email }}
                        </option>
                    @endforeach
                </select>

                @error('pelanggan_id')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
        @endif

        <div class="form-group">
            <label for="label">Label Alamat</label>

            <input type="text"
                   name="label"
                   id="label"
                   class="form-control"
                   placeholder="Contoh: Rumah, Kantor"
                   value="{{ old('label', $alamat->label ?? '') }}"
                   required>

            @error('label')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="alamat">Alamat Lengkap</label>

            <textarea name="alamat"
                      id="alamat"
                      class="form-control"
                      rows="4"
                      required>{{ old('alamat', $alamat->alamat ?? '') }}</textarea>

            @error('alamat')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="kota">Kota</label>

            <input type="text"
                   name="kota"
                   id="kota"
                   class="form-control"
                   value="{{ old('kota', $alamat->kota ?? '') }}"
                   required>

            @error('kota')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="provinsi">Provinsi</label>

            <input type="text"
                   name="provinsi"
                   id="provinsi"
                   class="form-control"
                   value="{{ old('provinsi', $alamat->provinsi ?? '') }}"
                   required>

            @error('provinsi')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="kode_pos">Kode Pos</label>

            <input type="text"
                   name="kode_pos"
                   id="kode_pos"
                   class="form-control"
                   value="{{ old('kode_pos', $alamat->kode_pos ?? '') }}"
                   required>

            @error('kode_pos')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="status">Status Alamat</label>

            <select name="status" id="status" class="form-control" required>
                <option value="">Pilih Status</option>

                @foreach (['Utama', 'Alternatif'] as $status)
                    <option value="{{ $status }}"
                        {{ old('status', $alamat->status ?? '') === $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>

            @error('status')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions" style="margin-top: 25px;">
            <button type="submit" class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('alamat.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </div>

    </form>

</div>

@endsection
