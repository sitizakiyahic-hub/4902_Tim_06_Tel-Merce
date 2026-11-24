<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    // MENAMPILKAN DATA PELANGGAN
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $pelanggan = Pelanggan::orderByDesc('id')->get();
        } else {
            $pelanggan = Pelanggan::where(
                'user_id',
                auth()->id()
            )->get();
        }

        return view('pelanggan.index', compact('pelanggan'));
    }

    // FORM TAMBAH PELANGGAN
    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('pelanggan.form');
    }

    // MENAMBAHKAN PELANGGAN SEKALIGUS MEMBUAT AKUN LOGIN
    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:daftar_pelanggan,email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'member' => 'required|in:Gold,Platinum,Silver',
        ]);

        DB::transaction(function () use ($validated) {

            // Membuat akun login pelanggan
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => 'user123',
                'role' => 'user',
            ]);

            // Membuat data pelanggan dan menghubungkannya ke akun
            Pelanggan::create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
                'member' => $validated['member'],
            ]);
        });

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan dan akun login berhasil dibuat. Password awal: user123');
    }

    // DETAIL PELANGGAN
    public function show(string $id)
    {
        abort(404);
    }

    // FORM EDIT PELANGGAN
    public function edit(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort_unless(
                $pelanggan->user_id === auth()->id(),
                403
            );
        }

        return view('pelanggan.form', compact('pelanggan'));
    }

    // MEMPERBARUI DATA PELANGGAN
    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $isAdmin = auth()->user()->role === 'admin';

        if (! $isAdmin) {
            abort_unless(
                $pelanggan->user_id === auth()->id(),
                403
            );
        }

        $rules = [
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'member' => 'required|in:Gold,Platinum,Silver',
        ];

        if ($isAdmin) {
            $rules['email'] = [
                'required',
                'email',
                'max:100',
                Rule::unique('daftar_pelanggan', 'email')
                    ->ignore($pelanggan->id),
                Rule::unique('users', 'email')
                    ->ignore($pelanggan->user_id),
            ];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($pelanggan, $validated, $isAdmin) {

            $pelanggan->update([
                'nama' => $validated['nama'],
                'no_hp' => $validated['no_hp'],
                'member' => $validated['member'],
            ]);

            if ($isAdmin) {
                $pelanggan->update([
                    'email' => $validated['email'],
                ]);

                // Sinkronisasi nama dan email akun login
                if ($pelanggan->user) {
                    $pelanggan->user->update([
                        'name' => $validated['nama'],
                        'email' => $validated['email'],
                    ]);
                }
            } else {
                // User boleh mengubah nama profilnya sendiri
                if ($pelanggan->user) {
                    $pelanggan->user->update([
                        'name' => $validated['nama'],
                    ]);
                }
            }
        });

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // MENGHAPUS PELANGGAN
    public function destroy(string $id)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $pelanggan = Pelanggan::findOrFail($id);

        DB::transaction(function () use ($pelanggan) {

            // Menghapus akun login yang terhubung
            if ($pelanggan->user) {
                $pelanggan->user->delete();
            }

            $pelanggan->delete();
        });

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan dan akun login berhasil dihapus.');
    }
}
