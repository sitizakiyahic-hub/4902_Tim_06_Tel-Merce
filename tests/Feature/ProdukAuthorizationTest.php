<?php

use App\Models\Produk;
use App\Models\User;

function validProdukPayload(array $overrides = []): array
{
    return array_merge([
        'nama' => 'Jaket Kanvas UMKM',
        'sku' => 'JKT-'.fake()->unique()->numberBetween(100, 999),
        'merek' => 'NusaWear',
        'kategori' => 'Fashion',
        'berat' => 450,
        'warna' => 'Olive',
        'ukuran' => 'L',
        'stok' => 15,
        'harga' => 175000,
    ], $overrides);
}

test('guest and customer can view catalog and product detail without management buttons', function () {
    $produk = Produk::factory()->create(['nama' => 'Batik Tulis Solo']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Batik Tulis Solo')
        ->assertDontSee('+ Tambah Produk')
        ->assertDontSee('btn-hapus', false);

    $this->get("/produk/{$produk->id}")
        ->assertOk()
        ->assertSee('Batik Tulis Solo');

    $customer = User::factory()->customer()->create();

    $this->actingAs($customer)
        ->get('/')
        ->assertOk()
        ->assertSee('Batik Tulis Solo')
        ->assertDontSee('+ Tambah Produk')
        ->assertDontSee('btn-edit', false)
        ->assertDontSee('btn-hapus', false);
});

test('guest is redirected to login on all protected produk routes', function () {
    $produk = Produk::factory()->create();

    $this->get('/produk')->assertRedirect('/login');
    $this->get('/produk.php')->assertRedirect('/login');
    $this->get("/produk/{$produk->id}/edit")->assertRedirect('/login');
    $this->post('/produk', validProdukPayload())->assertRedirect('/login');
    $this->post('/produk.php', validProdukPayload())->assertRedirect('/login');
    $this->put("/produk/{$produk->id}", validProdukPayload())->assertRedirect('/login');
    $this->post('/hapus', ['id' => $produk->id])->assertRedirect('/login');
    $this->post('/hapus.php', ['id' => $produk->id])->assertRedirect('/login');
    $this->delete("/produk/{$produk->id}")->assertRedirect('/login');
    $this->patch("/produk/{$produk->id}/toggle-lock")->assertRedirect('/login');
});

test('customer receives 403 forbidden on all produk write and management routes', function () {
    $customer = User::factory()->customer()->create();
    $produk = Produk::factory()->create();

    $this->actingAs($customer)->get('/produk')->assertForbidden();
    $this->actingAs($customer)->get('/produk.php')->assertForbidden();
    $this->actingAs($customer)->get("/produk/{$produk->id}/edit")->assertForbidden();
    $this->actingAs($customer)->post('/produk', validProdukPayload())->assertForbidden();
    $this->actingAs($customer)->post('/produk.php', validProdukPayload())->assertForbidden();
    $this->actingAs($customer)->put("/produk/{$produk->id}", validProdukPayload())->assertForbidden();
    $this->actingAs($customer)->post('/hapus', ['id' => $produk->id])->assertForbidden();
    $this->actingAs($customer)->post('/hapus.php', ['id' => $produk->id])->assertForbidden();
    $this->actingAs($customer)->delete("/produk/{$produk->id}")->assertForbidden();
    $this->actingAs($customer)->patch("/produk/{$produk->id}/toggle-lock")->assertForbidden();
});

test('staff creates produk with server-assigned ownership and cannot spoof user_id or is_locked', function () {
    $staff = User::factory()->staff()->create();
    $otherUser = User::factory()->admin()->create();

    $this->actingAs($staff)->post('/produk', validProdukPayload([
        'sku' => 'STF-100',
        'user_id' => $otherUser->id,
        'is_locked' => true,
    ]))->assertRedirect('/');

    $produk = Produk::where('sku', 'STF-100')->first();

    expect($produk)->not->toBeNull()
        ->and($produk->user_id)->toBe($staff->id)
        ->and($produk->is_locked)->toBeFalse();
});

test('staff can update own unlocked produk but gets 403 on other staff or locked produk including legacy id params', function () {
    $staffA = User::factory()->staff()->create();
    $staffB = User::factory()->staff()->create();

    $milikSendiri = Produk::factory()->ownedBy($staffA)->create(['nama' => 'Produk A']);
    $milikLain = Produk::factory()->ownedBy($staffB)->create(['nama' => 'Produk B']);
    $terkunci = Produk::factory()->ownedBy($staffA)->locked()->create(['nama' => 'Produk Terkunci']);

    $this->actingAs($staffA)
        ->get("/produk/{$milikSendiri->id}/edit")
        ->assertOk()
        ->assertSee('Produk A');

    $this->actingAs($staffA)
        ->put("/produk/{$milikSendiri->id}", validProdukPayload(['nama' => 'Produk A Revisi', 'sku' => $milikSendiri->sku]))
        ->assertRedirect('/');

    expect($milikSendiri->fresh()->nama)->toBe('Produk A Revisi');

    // IDOR prevention via both REST route and legacy ?id= route
    $this->actingAs($staffA)->get("/produk/{$milikLain->id}/edit")->assertForbidden();
    $this->actingAs($staffA)->get("/produk?id={$milikLain->id}")->assertForbidden();
    $this->actingAs($staffA)->put("/produk/{$milikLain->id}", validProdukPayload(['sku' => $milikLain->sku]))->assertForbidden();
    $this->actingAs($staffA)->post("/produk?id={$milikLain->id}", validProdukPayload(['sku' => $milikLain->sku]))->assertForbidden();

    // Locked product prevention
    $this->actingAs($staffA)->get("/produk/{$terkunci->id}/edit")->assertForbidden();
    $this->actingAs($staffA)->put("/produk/{$terkunci->id}", validProdukPayload(['sku' => $terkunci->sku]))->assertForbidden();

    // Staff cannot delete or toggle lock
    $this->actingAs($staffA)->post('/hapus', ['id' => $milikSendiri->id])->assertForbidden();
    $this->actingAs($staffA)->delete("/produk/{$milikSendiri->id}")->assertForbidden();
    $this->actingAs($staffA)->patch("/produk/{$milikSendiri->id}/toggle-lock")->assertForbidden();
});

test('admin can toggle lock, edit any produk, and delete any produk', function () {
    $admin = User::factory()->admin()->create();
    $staff = User::factory()->staff()->create();
    $produk = Produk::factory()->ownedBy($staff)->create(['nama' => 'Tas Kulit']);

    $this->actingAs($admin)
        ->patch("/produk/{$produk->id}/toggle-lock")
        ->assertRedirect('/');

    expect($produk->fresh()->is_locked)->toBeTrue();

    $this->actingAs($admin)
        ->put("/produk/{$produk->id}", validProdukPayload(['nama' => 'Tas Kulit Terverifikasi', 'sku' => $produk->sku]))
        ->assertRedirect('/');

    expect($produk->fresh()->nama)->toBe('Tas Kulit Terverifikasi');

    $this->actingAs($admin)
        ->delete("/produk/{$produk->id}")
        ->assertRedirect('/');

    expect(Produk::find($produk->id))->toBeNull();
});

test('blade views escape dangerous html in product and user names', function () {
    $user = User::factory()->admin()->create([
        'name' => 'Admin <script>alert("u")</script>',
    ]);
    Produk::factory()->ownedBy($user)->create([
        'nama' => 'Kopi <script>alert("p")</script>',
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertOk()
        ->assertSee('&lt;script&gt;', false)
        ->assertDontSee('<script>alert("p")</script>', false)
        ->assertDontSee('<script>alert("u")</script>', false);
});
