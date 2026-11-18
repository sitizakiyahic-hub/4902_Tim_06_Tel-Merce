<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($voucher) ? 'Edit' : 'Tambah' }} Voucher</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

    <div class="app-container">
        <div class="card form-card">
            <div class="card-header">
                <div>
                    <h3>{{ isset($voucher) ? 'Edit Voucher' : 'Tambah Voucher Baru' }}</h3>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ isset($voucher) ? route('vouchers.update', $voucher) : route('vouchers.store') }}" method="POST">
                @csrf
                @isset($voucher)
                    @method('PUT')
                @endisset

                <div class="form-group">
                    <label for="kode">Kode Voucher</label>
                    <input
                        type="text"
                        id="kode"
                        name="kode"
                        class="form-control"
                        placeholder="Contoh: PROMO10"
                        value="{{ old('kode', $voucher->kode ?? '') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis Diskon</label>
                    <select id="jenis" name="jenis" class="form-control" required>
                        @foreach (['Persen (%)' => 'Persentase (%)', 'Nominal (Rp)' => 'Nominal (Rp)'] as $val => $label)
                            <option value="{{ $val }}" {{ old('jenis', $voucher->jenis ?? '') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="nilai">Besar Diskon</label>
                    <input
                        type="number"
                        id="nilai"
                        name="nilai"
                        class="form-control"
                        placeholder="Contoh: 10 atau 15000"
                        value="{{ old('nilai', $voucher->nilai ?? '') }}"
                        required
                        min="0"
                    >
                    <p class="form-hint">Masukkan angka saja. Satuan (% atau Rp) menyesuaikan jenis diskon.</p>
                </div>

                <div class="form-group">
                    <label for="syarat">Syarat Minimal Belanja (Rp)</label>
                    <input
                        type="number"
                        id="syarat"
                        name="syarat"
                        class="form-control"
                        placeholder="Contoh: 50000"
                        value="{{ old('syarat', $voucher->syarat ?? '') }}"
                        required
                        min="0"
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($voucher) ? 'Simpan Perubahan' : 'Simpan Voucher' }}
                    </button>
                    <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
