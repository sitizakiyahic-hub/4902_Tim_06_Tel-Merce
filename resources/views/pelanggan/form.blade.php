
@extends('layouts.app')

@section('title', isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan')

@section('page-title', isset($pelanggan) ? 'Edit Pelanggan' : 'Tambah Pelanggan')

@section('page-subtitle', 'Kelola informasi pelanggan Tel-Merce')

@section('content')

<div class="card form-card" style="max-width: 750px;">

    <div class="card-header">
        <div>
            <h3>
                {{ isset($pelanggan) ? 'Form Edit Pelanggan' : 'Form Tambah Pelanggan' }}
            </h3>
            <p>
                {{ isset($pelanggan)
                    ? 'Perbarui informasi data pelanggan.'
                    : 'Masukkan informasi pelanggan baru.' }}
            </p>
        </div>
    </div>

    <form action="{{ isset($pelanggan) ? route('pelanggan.update', $pelanggan->id) : route('pelanggan.store') }}"
          method="POST">

        @csrf

        @if (isset($pelanggan))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="nama">Nama Pelanggan</label>
            <input type="text"
                   id="nama"
                   name="nama"
                   class="form-control"
                   value="{{ old('nama', $pelanggan->nama ?? '') }}"
                   required>

            @error('nama')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>

            @if (auth()->user()->role === 'admin')
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email', $pelanggan->email ?? '') }}"
                       required>

                @error('email')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            @else
                <input type="email"
                       id="email"
                       class="form-control"
                       value="{{ $pelanggan->email }}"
                       readonly
                       style="background: #f5f2ff;">
                <p class="form-hint">Email tidak dapat diubah.</p>
            @endif
        </div>

        <div class="form-group">
            <label for="no_hp">Nomor HP</label>
            <input type="text"
                   id="no_hp"
                   name="no_hp"
                   class="form-control"
                   value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}"
                   required>

            @error('no_hp')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="member">Jenis Member</label>
            <select id="member" name="member" class="form-control" required>
                <option value="">Pilih Member</option>

                @foreach (['Gold', 'Platinum', 'Silver'] as $jenis)
                    <option value="{{ $jenis }}"
                        {{ old('member', $pelanggan->member ?? '') === $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                @endforeach
            </select>

            @error('member')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                {{ isset($pelanggan) ? 'Simpan Perubahan' : 'Simpan Pelanggan' }}
            </button>

            <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection
