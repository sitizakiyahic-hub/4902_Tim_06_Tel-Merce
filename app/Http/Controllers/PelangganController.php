<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $pelanggan = Pelanggan::orderByDesc('id')->get();
        } else {
            $pelanggan = Pelanggan::where(
                'email',
                auth()->user()->email
            )->get();
        }

        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('pelanggan.form');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:daftar_pelanggan,email',
            'no_hp' => 'required|string|max:20',
            'member' => 'required|in:Gold,Platinum,Silver',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if (auth()->user()->role !== 'admin') {
            abort_unless(
                $pelanggan->email === auth()->user()->email,
                403
            );
        }

        return view('pelanggan.form', compact('pelanggan'));
    }

    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $isAdmin = auth()->user()->role === 'admin';

        if (! $isAdmin) {
            abort_unless(
                $pelanggan->email === auth()->user()->email,
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
            ];
        }

        $validated = $request->validate($rules);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
