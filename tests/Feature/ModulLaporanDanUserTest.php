<?php

use App\Enums\UserRole;
use App\Models\Produk;
use App\Models\User;

test('stock report is accessible by admin and staff only, and csv export by admin only', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create();
    $customer = User::factory()->customer()->create();

    Produk::factory()->ownedBy($staff)->create([
        'nama' => '=SUM(1+1)',
        'sku' => 'EXP-01',
        'stok' => 3,
        'harga' => 50000,
    ]);

    $this->get('/laporan/stok')->assertRedirect('/login');
    $this->get('/laporan/export')->assertRedirect('/login');

    $this->actingAs($customer)->get('/laporan/stok')->assertForbidden();
    $this->actingAs($customer)->get('/laporan/export')->assertForbidden();

    $this->actingAs($staff)
        ->get('/laporan/stok')
        ->assertOk()
        ->assertSee('Laporan Ringkasan Stok')
        ->assertDontSee('Export CSV');
    $this->actingAs($staff)->get('/laporan/export')->assertForbidden();

    $this->actingAs($admin)
        ->get('/laporan/stok')
        ->assertOk()
        ->assertSee('Export CSV');

    $exportResponse = $this->actingAs($admin)->get('/laporan/export');
    $exportResponse->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    $csvContent = $exportResponse->streamedContent();
    expect($csvContent)
        ->toContain('EXP-01')
        ->toContain("'=SUM(1+1)");
});

test('user management routes are restricted to admin and prevent self demotion, self deactivation, or self deletion', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create();
    $customer = User::factory()->customer()->create();

    $this->get('/admin/users')->assertRedirect('/login');

    $this->actingAs($customer)->get('/admin/users')->assertForbidden();
    $this->actingAs($customer)->delete("/admin/users/{$staff->id}")->assertForbidden();
    $this->actingAs($staff)->get('/admin/users')->assertForbidden();
    $this->actingAs($staff)->patch("/admin/users/{$customer->id}/role", ['role' => 'admin'])->assertForbidden();
    $this->actingAs($staff)->patch("/admin/users/{$customer->id}/toggle-active")->assertForbidden();
    $this->actingAs($staff)->delete("/admin/users/{$customer->id}")->assertForbidden();

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertOk()
        ->assertSee($customer->email);

    // Admin promotes customer to staff
    $this->actingAs($admin)
        ->patch("/admin/users/{$customer->id}/role", ['role' => 'staff'])
        ->assertRedirect('/admin/users');
    expect($customer->fresh()->role)->toBe(UserRole::Staff);

    // Admin deactivates staff
    $this->actingAs($admin)
        ->patch("/admin/users/{$staff->id}/toggle-active")
        ->assertRedirect('/admin/users');
    expect($staff->fresh()->is_active)->toBeFalse();

    // Self protection
    $this->actingAs($admin)
        ->patch("/admin/users/{$admin->id}/role", ['role' => 'customer'])
        ->assertRedirect('/admin/users');
    expect($admin->fresh()->role)->toBe(UserRole::Admin);

    $this->actingAs($admin)
        ->patch("/admin/users/{$admin->id}/toggle-active")
        ->assertRedirect('/admin/users');
    expect($admin->fresh()->is_active)->toBeTrue();

    $this->actingAs($admin)
        ->delete("/admin/users/{$admin->id}")
        ->assertRedirect('/admin/users');
    expect($admin->fresh())->not->toBeNull();

    // Admin can delete another user
    $this->actingAs($admin)
        ->delete("/admin/users/{$customer->id}")
        ->assertRedirect('/admin/users');
    expect(User::find($customer->id))->toBeNull();
});

test('authenticated user can update profile name without changing role or active status', function () {
    $customer = User::factory()->customer()->create(['name' => 'Nama Awal']);

    $this->get('/profil')->assertRedirect('/login');

    $this->actingAs($customer)
        ->get('/profil')
        ->assertOk()
        ->assertSee('Pelanggan');

    $this->actingAs($customer)
        ->put('/profil', [
            'name' => 'Nama Baru',
            'role' => 'admin',
            'is_active' => false,
        ])
        ->assertRedirect('/profil');

    $fresh = $customer->fresh();
    expect($fresh->name)->toBe('Nama Baru')
        ->and($fresh->role)->toBe(UserRole::Customer)
        ->and($fresh->is_active)->toBeTrue();
});

test('authenticated user can update password when current password matches', function () {
    $user = User::factory()->customer()->create([
        'password' => 'lama12345',
    ]);

    $this->actingAs($user)
        ->from('/profil')
        ->put('/profil', [
            'name' => $user->name,
            'current_password' => 'salah123',
            'password' => 'baru12345',
            'password_confirmation' => 'baru12345',
        ])
        ->assertRedirect('/profil')
        ->assertSessionHasErrors('current_password');

    $this->actingAs($user)
        ->put('/profil', [
            'name' => $user->name,
            'current_password' => 'lama12345',
            'password' => 'baru12345',
            'password_confirmation' => 'baru12345',
        ])
        ->assertRedirect('/profil')
        ->assertSessionHasNoErrors();
});

test('database seeder seeds role accounts and sample produk idempotently', function () {
    $this->seed();
    $this->seed();

    $admin = User::where('email', 'admin@umkm.test')->first();
    $staff = User::where('email', 'staff@umkm.test')->first();
    $customer = User::where('email', 'customer@umkm.test')->first();

    expect(User::count())->toBe(3)
        ->and($admin?->role)->toBe(UserRole::Admin)
        ->and($admin?->is_active)->toBeTrue()
        ->and($staff?->role)->toBe(UserRole::Staff)
        ->and($staff?->is_active)->toBeTrue()
        ->and($customer?->role)->toBe(UserRole::Customer)
        ->and($customer?->is_active)->toBeTrue()
        ->and(Produk::count())->toBe(3);
});