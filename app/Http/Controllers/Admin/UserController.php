<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('manage-users');

        return view('admin.users', [
            'users' => User::orderBy('id')->get(),
            'roles' => UserRole::cases(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('manage-users');

        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')
                ->with('pesan', 'Anda tidak dapat mengubah peran akun Anda sendiri.')
                ->with('pesan_tipe', 'hapus');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
        ], [
            'role.required' => 'Peran pengguna wajib dipilih.',
        ]);

        $user->role = UserRole::from($validated['role']);
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('pesan', "Peran {$user->name} berhasil diubah menjadi {$user->role->label()}.");
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('manage-users');

        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')
                ->with('pesan', 'Anda tidak dapat menonaktifkan akun Anda sendiri.')
                ->with('pesan_tipe', 'hapus');
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.users.index')
            ->with('pesan', "Akun {$user->name} berhasil {$status}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('manage-users');

        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')
                ->with('pesan', 'Anda tidak dapat menghapus akun Anda sendiri.')
                ->with('pesan_tipe', 'hapus');
        }

        $nama = $user->name;

        try {
            $user->delete();
        } catch (QueryException) {
            return redirect()->route('admin.users.index')
                ->with('pesan', "Akun {$nama} tidak dapat dihapus karena masih terkait dengan data lain.")
                ->with('pesan_tipe', 'hapus');
        }

        return redirect()->route('admin.users.index')
            ->with('pesan', "Akun {$nama} berhasil dihapus.")
            ->with('pesan_tipe', 'hapus');
    }
        
}