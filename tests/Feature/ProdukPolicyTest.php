<?php

use App\Models\Produk;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

dataset('produk_policy_cases', [
    'guest' => [fn () => null, 'ownUnlocked', ['viewAny' => true, 'view' => true, 'create' => false, 'update' => false, 'delete' => false, 'toggleLock' => false]],
    'customer' => [fn () => User::factory()->customer()->create(), 'ownUnlocked', ['viewAny' => true, 'view' => true, 'create' => false, 'update' => false, 'delete' => false, 'toggleLock' => false]],
    'staff on own unlocked' => [fn () => User::factory()->staff()->create(), 'ownUnlocked', ['viewAny' => true, 'view' => true, 'create' => true, 'update' => true, 'delete' => false, 'toggleLock' => false]],
    'staff on own locked' => [fn () => User::factory()->staff()->create(), 'ownLocked', ['viewAny' => true, 'view' => true, 'create' => true, 'update' => false, 'delete' => false, 'toggleLock' => false]],
    'staff on other staff product' => [fn () => User::factory()->staff()->create(), 'otherOwned', ['viewAny' => true, 'view' => true, 'create' => true, 'update' => false, 'delete' => false, 'toggleLock' => false]],
    'admin on other locked product' => [fn () => User::factory()->admin()->create(), 'otherLocked', ['viewAny' => true, 'view' => true, 'create' => true, 'update' => true, 'delete' => true, 'toggleLock' => true]],
]);

test('produk policy enforces rbac and abac rules accurately', function (Closure $makeUser, string $scenario, array $expected) {
    $actor = $makeUser();
    $otherStaff = User::factory()->staff()->create();

    $produk = match ($scenario) {
        'ownUnlocked' => Produk::factory()->create(['user_id' => $actor?->id ?? $otherStaff->id, 'is_locked' => false]),
        'ownLocked' => Produk::factory()->locked()->create(['user_id' => $actor?->id ?? $otherStaff->id]),
        'otherOwned' => Produk::factory()->ownedBy($otherStaff)->create(['is_locked' => false]),
        'otherLocked' => Produk::factory()->ownedBy($otherStaff)->locked()->create(),
    };

    $gate = Gate::forUser($actor);

    expect($gate->allows('viewAny', Produk::class))->toBe($expected['viewAny'])
        ->and($gate->allows('view', $produk))->toBe($expected['view'])
        ->and($gate->allows('create', Produk::class))->toBe($expected['create'])
        ->and($gate->allows('update', $produk))->toBe($expected['update'])
        ->and($gate->allows('delete', $produk))->toBe($expected['delete'])
        ->and($gate->allows('toggleLock', $produk))->toBe($expected['toggleLock']);
})->with('produk_policy_cases');

test('role gates match user roles', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create();
    $customer = User::factory()->customer()->create();

    expect(Gate::forUser($admin)->allows('manage-users'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('view-stock-report'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('export-produk'))->toBeTrue()
        ->and(Gate::forUser($staff)->allows('manage-users'))->toBeFalse()
        ->and(Gate::forUser($staff)->allows('view-stock-report'))->toBeTrue()
        ->and(Gate::forUser($staff)->allows('export-produk'))->toBeFalse()
        ->and(Gate::forUser($customer)->allows('manage-users'))->toBeFalse()
        ->and(Gate::forUser($customer)->allows('view-stock-report'))->toBeFalse()
        ->and(Gate::forUser($customer)->allows('export-produk'))->toBeFalse();
});
