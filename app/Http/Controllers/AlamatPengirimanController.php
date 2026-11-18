<?php

namespace App\Http\Controllers;

use App\Models\AlamatPengiriman;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlamatPengirimanController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $alamat = AlamatPengiriman::with('pelanggan')
                ->orderByDesc('id')
                ->get();
        } else {
            $alamat = AlamatPengiriman::with('pelanggan')
                ->whereHas('pelanggan', function ($query) {
                    $query->where('email', auth()->user()->email);
                })
                ->orderByDesc('id')
                ->get();
        }

        return view('alamat.index', compact('alamat'));
    }

    public function create()
    {
        $isAdmin = auth()->user()->role === 'admin';

        $pelanggan = Pelanggan::orderBy('nama')->get();

        if (! $isAdmin) {
            abort_unless(
                Pelanggan::where('email', auth()->user()->email)->exists(),
                403,
                'Profil pelanggan belum tersedia.'
            );
        }

        return view('alamat.form', compact('pelanggan', 'isAdmin'));
    }

    public function store(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';

        $rules = [
            'label' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'status' => 'required|string|max:20',
        ];

        if ($isAdmin) {
            $rules['pelanggan_id'] = 'required|exists:daftar_pelanggan,id';
        }

        $validated = $request->validate($rules);

        if (! $isAdmin) {
            $pelanggan = Pelanggan::where(
                'email',
                auth()->user()->email
            )->firstOrFail();

            $validated['pelanggan_id'] = $pelanggan->id;
        }

        AlamatPengiriman::create($validated);

        return redirect()->route('alamat.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        $alamat = AlamatPengiriman::with('pelanggan')->findOrFail($id);

        $isAdmin = auth()->user()->role === 'admin';

        if (! $isAdmin) {
            abort_unless(
                $alamat->pelanggan->email === auth()->user()->email,
                403
            );
        }

        $pelanggan = Pelanggan::orderBy('nama')->get();

        return view('alamat.form', compact('alamat', 'pelanggan', 'isAdmin'));
    }

    public function update(Request $request, string $id)
    {
        $alamat = AlamatPengiriman::with('pelanggan')->findOrFail($id);

        $isAdmin = auth()->user()->role === 'admin';

        if (! $isAdmin) {
            abort_unless(
                $alamat->pelanggan->email === auth()->user()->email,
                403
            );
        }

        $rules = [
            'label' => 'required|string|max:50',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'status' => 'required|string|max:20',
        ];

        if ($isAdmin) {
            $rules['pelanggan_id'] = 'required|exists:daftar_pelanggan,id';
        }

        $validated = $request->validate($rules);

        if (! $isAdmin) {
            unset($validated['pelanggan_id']);
        }

        $alamat->update($validated);

        return redirect()->route('alamat.index')
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $alamat = AlamatPengiriman::with('pelanggan')->findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort_unless(
                $alamat->pelanggan->email === auth()->user()->email,
                403
            );
        }

        $alamat->delete();

        return redirect()->route('alamat.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }
}
