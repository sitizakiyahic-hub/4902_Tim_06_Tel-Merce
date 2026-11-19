<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = $this->upsertUser('Administrator UMKM', 'admin@umkm.test', UserRole::Admin, true);
        $staff = $this->upsertUser('Staff Katalog', 'staff@umkm.test', UserRole::Staff, true);
        $this->upsertUser('Pelanggan UMKM', 'customer@umkm.test', UserRole::Customer, true);

        if (Produk::count() === 0) {
            Produk::factory()->ownedBy($staff)->create([
                'nama' => 'Kaos Polos Katun Combed 30s',
                'sku' => 'FSH-001',
                'merek' => 'LokalWear',
                'kategori' => 'Fashion',
                'berat' => 200,
                'warna' => 'Hitam',
                'ukuran' => 'L',
                'stok' => 35,
                'harga' => 75000,
                'is_locked' => false,
            ]);

            Produk::factory()->ownedBy($staff)->locked()->create([
                'nama' => 'Kopi Arabika Gayo 250g',
                'sku' => 'MKN-002',
                'merek' => 'NusantaraRoast',
                'kategori' => 'Makanan',
                'berat' => 250,
                'warna' => 'Cokelat',
                'ukuran' => '-',
                'stok' => 4,
                'harga' => 95000,
            ]);

            Produk::factory()->ownedBy($admin)->create([
                'nama' => 'Tas Anyaman Rotan Handmade',
                'sku' => 'KRJ-003',
                'merek' => 'KaryaDesa',
                'kategori' => 'Kerajinan',
                'berat' => 450,
                'warna' => 'Natural',
                'ukuran' => 'M',
                'stok' => 12,
                'harga' => 185000,
                'is_locked' => false,
            ]);
        }
    }

    private function upsertUser(string $name, string $email, UserRole $role, bool $isActive): User
    {
        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name;
        if (! $user->exists) {
            $user->password = Hash::make('password');
            $user->email_verified_at = now();
        }
        $user->role = $role;
        $user->is_active = $isActive;
        $user->save();

        return $user;
    }
}