<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar Akun - UMKM Toko Online</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container auth-container">
  <h2>Daftar Akun Pelanggan</h2>

  @if ($errors->any())
    <div class="alert alert-error" style="margin-top: 16px;">
      @foreach ($errors->all() as $err)
        <div>{{ $err }}</div>
      @endforeach
    </div>
  @endif

  <form action="{{ route('register.store') }}" method="post">
    @csrf
    <div class="form-grid" style="grid-template-columns: 1fr;">
      <div class="field">
        <label for="name">Nama Lengkap</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
      </div>

      <div class="field">
        <label for="password">Kata Sandi</label>
        <div class="password-wrap">
          <input id="password" name="password" type="password" required>
          <button type="button" class="btn-toggle-pass" data-toggle-password="password" aria-label="Tampilkan kata sandi" aria-pressed="false"></button>
        </div>
      </div>

      <div class="field">
        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
        <div class="password-wrap">
          <input id="password_confirmation" name="password_confirmation" type="password" required>
          <button type="button" class="btn-toggle-pass" data-toggle-password="password_confirmation" aria-label="Tampilkan kata sandi" aria-pressed="false"></button>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('login') }}" class="btn-secondary">Sudah punya akun? Masuk</a>
      <button type="submit" class="btn">Daftar</button>
    </div>
  </form>
</div>

</body>
</html>