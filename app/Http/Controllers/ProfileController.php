<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profil', [
            'user' => $user,
            'hakAkses' => $this->ringkasanHakAkses($user->role),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'current_password.required_with' => 'Kata sandi saat ini wajib diisi untuk mengubah kata sandi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->name = $validated['name'];

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('profil.edit')
            ->with('pesan', 'Profil akun berhasil diperbarui.');
    }

    /**
     * @return list<string>
     */
    private function ringkasanHakAkses(UserRole $role): array
    {
        return match ($role) {
            UserRole::Admin => [
                'Melihat seluruh daftar & detail katalog produk',
                'Menambah, mengubah, mengunci/buka kunci, dan menghapus semua produk',
                'Melihat laporan ringkasan stok dan mengekspor katalog CSV',
                'Mengelola akun pengguna, peran (role), dan status aktif',
            ],
            UserRole::Staff => [
                'Melihat seluruh daftar & detail katalog produk',
                'Menambah produk baru (otomatis tercatat sebagai pemilik)',
                'Mengubah produk milik sendiri selama belum dikunci oleh Admin',
                'Melihat laporan ringkasan stok',
            ],
            UserRole::Customer => [
                'Melihat daftar & detail katalog produk',
                'Mengelola profil dan kata sandi akun pribadi',
            ],
        };
    }
}
