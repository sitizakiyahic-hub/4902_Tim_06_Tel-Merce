
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Voucher</title>

    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 35px 20px;
            font-family: Arial, sans-serif;
            background: #f5f2ff;
            color: #30234a;
        }

        .voucher-container {
            max-width: 1100px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(75, 41, 149, 0.08);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            padding-bottom: 22px;
            border-bottom: 2px solid #eee8fc;
        }

        .header h2 {
            margin: 0;
            font-size: 27px;
            color: #4b2995;
        }

        .header p {
            margin: 7px 0 0;
            color: #8b819e;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border: none;
            border-radius: 9px;
            background: #6d45c5;
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn:hover {
            background: #4b2995;
        }

        .userbar {
            margin: 22px 0;
            padding: 15px 18px;
            background: #f7f3ff;
            border: 1px solid #eee6ff;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            color: #655778;
            font-size: 14px;
        }

        .userbar b {
            color: #4b2995;
        }

        .inline {
            display: inline;
        }

        .btn-red {
            padding: 9px 15px;
            border: none;
            border-radius: 8px;
            background: #fce8e8;
            color: #d14343;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-red:hover {
            background: #f8d1d1;
        }

        .section-title {
            margin: 30px 0 15px;
            font-size: 19px;
            color: #4b2995;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #eee8fc;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        thead {
            background: #6d45c5;
            color: white;
        }

        th {
            padding: 16px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #f0ebf8;
            font-size: 14px;
            color: #514663;
        }

        tbody tr:hover {
            background: #faf8ff;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .kode-voucher {
            font-weight: bold;
            color: #6d45c5;
        }

        .aksi {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .aksi .btn {
            padding: 8px 13px;
            font-size: 12px;
        }

        .aksi form {
            margin: 0;
        }

        .aksi .btn-red {
            padding: 8px 13px;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 35px 15px;
            color: #9287a5;
        }

        .empty-state .icon {
            font-size: 35px;
            margin-bottom: 10px;
        }

        .empty-state strong {
            display: block;
            color: #4b2995;
            margin-bottom: 7px;
            font-size: 16px;
        }

        .empty-state p {
            margin: 0;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px 10px;
            }

            .voucher-container {
                padding: 20px 15px;
            }

            .header h2 {
                font-size: 23px;
            }

            .header .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="voucher-container">

        <div class="header">
            <div>
                <h2>Data Voucher</h2>
                <p>Kelola voucher dan penawaran diskon</p>
            </div>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('vouchers.create') }}" class="btn">
                    + Tambah Voucher
                </a>
            @endif
        </div>

        <div class="userbar">
            <div>
                Login sebagai
                <b>{{ auth()->user()->name }}</b>
                ({{ auth()->user()->role }})
            </div>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-red">Logout</button>
            </form>
        </div>

        <h3 class="section-title">Daftar Voucher</h3>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Kode Voucher</th>
                        <th>Jenis</th>
                        <th>Nilai</th>
                        <th>Syarat Minimum</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($vouchers as $v)
                        <tr>
                            <td class="kode-voucher">
                                {{ $v->kode }}
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
                                @if (auth()->user()->role === 'admin')
                                    <div class="aksi">
                                        <a href="{{ route('vouchers.edit', $v) }}"
                                           class="btn">
                                            Edit
                                        </a>

                                        <form action="{{ route('vouchers.destroy', $v) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-red">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="icon">🎟️</div>
                                    <strong>Belum Ada Voucher</strong>
                                    <p>Voucher yang ditambahkan akan ditampilkan di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
