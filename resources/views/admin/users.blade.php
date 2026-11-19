<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manajemen Pengguna - UMKM Toko Online</title>
<link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

@include('partials.header')

<div class="container">
  <div class="toolbar">
    <h2>Manajemen Pengguna &amp; Hak Akses</h2>
    <a href="{{ route('produk.index') }}" class="btn-secondary">Kembali ke Katalog</a>
  </div>

  @if (session('pesan'))
    <div class="alert {{ session('pesan_tipe') === 'hapus' ? 'alert-error' : 'alert-sukses' }}">{{ session('pesan') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-error">
      @foreach ($errors->all() as $err)
        <div>{{ $err }}</div>
      @endforeach
    </div>
  @endif

  <div class="table-wrap">
    <table>
      <tr>
        <th class="tengah">No</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Peran Saat Ini</th>
        <th class="tengah">Status</th>
        <th>Ubah Peran</th>
        <th class="tengah">Aksi</th>
      </tr>
      @foreach ($users as $u)
        <tr>
          <td class="tengah">{{ $loop->iteration }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td><span class="badge badge-ok">{{ $u->role->label() }}</span></td>
          <td class="tengah">
            @if ($u->is_active)
              <span class="badge badge-ok">Aktif</span>
            @else
              <span class="badge badge-low">Nonaktif</span>
            @endif
          </td>
          <td>
            @if ($u->id === auth()->id())
              <span style="color: var(--muted); font-size: 0.85rem;">Akun Anda sendiri</span>
            @else
              <form action="{{ route('admin.users.role', $u) }}" method="post" class="inline-select">
                @csrf
                @method('PATCH')
                <select name="role" aria-label="Peran untuk {{ $u->name }}">
                  @foreach ($roles as $role)
                    <option value="{{ $role->value }}" {{ $u->role === $role ? 'selected' : '' }}>
                      {{ $role->label() }}
                    </option>
                  @endforeach
                </select>
                <button type="submit" class="btn-edit" style="height: 34px; padding: 0 12px;">Simpan</button>
              </form>
            @endif
          </td>
          <td class="tengah">
            @if ($u->id !== auth()->id())
              <div class="aksi">
                <form action="{{ route('admin.users.toggle-active', $u) }}" method="post" class="inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="{{ $u->is_active ? 'btn-hapus' : 'btn-edit' }}" style="height: 34px; padding: 0 12px;">
                    {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                </form>

                <form action="{{ route('admin.users.destroy', $u) }}" method="post" class="inline" data-confirm="Hapus pengguna ini?">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-hapus" style="height: 34px; padding: 0 12px;">Hapus</button>
                </form>
              </div>
            @else
              -
            @endif
          </td>
        </tr>
      @endforeach
    </table>
  </div>
</div>

</body>
</html>