<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Produk;
use App\Models\User;

class ProdukPolicy
{
    /**
     * Semua pengunjung (guest maupun user login) boleh melihat daftar katalog produk.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Semua pengunjung boleh melihat detail produk.
     */
    public function view(?User $user, Produk $produk): bool
    {
        return true;
    }

    /**
     * Admin dan Staff boleh menambahkan produk baru ke katalog.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Admin->value, UserRole::Staff->value);
    }

    /**
     * ABAC: Admin boleh mengubah semua produk; Staff hanya produk miliknya yang tidak dikunci.
     */
    public function update(User $user, Produk $produk): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isStaff()) {
            return $produk->user_id === $user->id && ! $produk->is_locked;
        }

        return false;
    }

    /**
     * Hanya Admin yang boleh menghapus produk dari katalog.
     */
    public function delete(User $user, Produk $produk): bool
    {
        return $user->isAdmin();
    }

    /**
     * Hanya Admin yang boleh mengunci atau membuka kunci produk.
     */
    public function toggleLock(User $user, Produk $produk): bool
    {
        return $user->isAdmin();
    }
}
