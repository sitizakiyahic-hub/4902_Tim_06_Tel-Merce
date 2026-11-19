<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profil Akun - UMKM Toko Online</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container">
  <div class="toolbar">
    <h2>Profil &amp; Hak Akses Akun</h2>
    <span class="badge badge-ok">Peran: {{ $user->role->label() }}</span>
  </div>

  @if (session('pesan'))
    <div class="alert alert-sukses">{{ session('pesan') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-error">
      @foreach ($errors->all() as $err)
        <div>{{ $err }}</div>
      @endforeach
    </div>
  @endif

  <form action="{{ route('profil.update') }}" method="post">
    @csrf
    @method('PUT')
    <div class="form-grid">
      <div class="field">
        <label for="name">Nama Lengkap</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input id="email" type="email" value="{{ $user->email }}" disabled>
      </div>

      <div class="field">
        <label for="current_password">Kata Sandi Saat Ini (opsional)</label>
        <div class="password-wrap">
          <input id="current_password" name="current_password" type="password">
          <button type="button" class="btn-toggle-pass" data-toggle-password="current_password" aria-label="Tampilkan kata sandi" aria-pressed="false"></button>
        </div>
      </div>

      <div class="field">
        <label for="password">Kata Sandi Baru (opsional)</label>
        <div class="password-wrap">
          <input id="password" name="password" type="password">
          <button type="button" class="btn-toggle-pass" data-toggle-password="password" aria-label="Tampilkan kata sandi" aria-pressed="false"></button>
        </div>
      </div>

      <div class="field full">
        <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
        <div class="password-wrap">
          <input id="password_confirmation" name="password_confirmation" type="password">
          <button type="button" class="btn-toggle-pass" data-toggle-password="password_confirmation" aria-label="Tampilkan kata sandi" aria-pressed="false"></button>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('produk.index') }}" class="btn-secondary">Kembali ke Katalog</a>
      <button type="submit" class="btn">Simpan Profil</button>
    </div>
  </form>

  <div class="info-box">
    <strong>Hak Akses Modul untuk Peran {{ $user->role->label() }}:</strong>
    <ul>
      @foreach ($hakAkses as $item)
        <li>{{ $item }}</li>
      @endforeach
    </ul>
  </div>
</div>

</body>
</html>