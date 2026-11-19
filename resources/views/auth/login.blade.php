<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk - UMKM Toko Online</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container auth-container">
  <h2>Masuk ke Akun</h2>

  @if (session('pesan'))
    <div class="alert alert-sukses" style="margin-top: 16px;">{{ session('pesan') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-error" style="margin-top: 16px;">
      @foreach ($errors->all() as $err)
        <div>{{ $err }}</div>
      @endforeach
    </div>
  @endif

  <form action="{{ route('login.attempt') }}" method="post">
    @csrf
    <div class="form-grid" style="grid-template-columns: 1fr;">
      <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
      </div>

      <div class="field">
        <label for="password">Kata Sandi</label>
        <div class="password-wrap">
          <input id="password" name="password" type="password" required>
          <button type="button" class="btn-toggle-pass" data-toggle-password="password" aria-label="Tampilkan kata sandi" aria-pressed="false"></button>
        </div>
      </div>

      <div class="field" style="flex-direction: row; align-items: center; gap: 8px;">
        <input id="remember" name="remember" type="checkbox" value="1" style="width: auto; box-shadow: none;">
        <label for="remember" style="margin: 0; font-weight: 500;">Ingat saya</label>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('register') }}" class="btn-secondary">Belum punya akun? Daftar</a>
      <button type="submit" class="btn">Masuk</button>
    </div>
  </form>

  @if (app()->isLocal())
    <div class="info-box">
      <strong>Akun Demo Seeder (kata sandi: <code>password</code>):</strong>
      <ul>
        <li>Admin: <code>admin@umkm.test</code></li>
        <li>Staff: <code>staff@umkm.test</code></li>
        <li>Pelanggan: <code>customer@umkm.test</code></li>
      </ul>
    </div>
  @endif
</div>

</body>
</html>
