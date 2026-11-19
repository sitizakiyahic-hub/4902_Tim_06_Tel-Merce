<?php

use App\Enums\UserRole;
use App\Http\Middleware\EnsureUserHasRole;
use App\Models\User;
use Illuminate\Support\Facades\Route;

test('global security headers middleware attaches expected headers', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});

test('user can login with valid credentials and session is regenerated', function () {
    $user = User::factory()->staff()->create([
        'email' => 'staff-login@umkm.test',
        'password' => 'rahasia123',
    ]);

    $oldSessionId = session()->getId();

    $response = $this->post('/login', [
        'email' => 'staff-login@umkm.test',
        'password' => 'rahasia123',
    ]);

    $response->assertRedirect('/');
    $this->assertAuthenticatedAs($user);
    expect(session()->getId())->not->toBe($oldSessionId);
});

test('login fails with invalid credentials and returns generic error', function () {
    User::factory()->create([
        'email' => 'user@umkm.test',
        'password' => 'rahasia123',
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => 'user@umkm.test',
        'password' => 'salah-password',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHasErrors(['email' => 'Email atau kata sandi salah.']);
    $this->assertGuest();
});

test('login route throttles repeated failed attempts', function () {
    User::factory()->create([
        'email' => 'brute@umkm.test',
        'password' => 'rahasia123',
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->post('/login', [
            'email' => 'brute@umkm.test',
            'password' => 'wrong',
        ])->assertRedirect();
    }

    $this->post('/login', [
        'email' => 'brute@umkm.test',
        'password' => 'wrong',
    ])->assertStatus(429);
});

test('inactive user cannot login and active session of deactivated user is terminated by middleware', function () {
    $inactive = User::factory()->staff()->inactive()->create([
        'email' => 'nonaktif-test@umkm.test',
        'password' => 'rahasia123',
    ]);

    $this->from('/login')->post('/login', [
        'email' => 'nonaktif-test@umkm.test',
        'password' => 'rahasia123',
    ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');
    $this->assertGuest();

    $this->actingAs($inactive)
        ->get('/')
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('registration assigns customer role regardless of injected role payload', function () {
    $response = $this->post('/register', [
        'name' => 'Pembeli Baru',
        'email' => 'pembeli@umkm.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
        'is_active' => false,
    ]);

    $response->assertRedirect('/');

    $user = User::where('email', 'pembeli@umkm.test')->first();
    expect($user)->not->toBeNull()
        ->and($user->role)->toBe(UserRole::Customer)
        ->and($user->is_active)->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

test('authenticated user is redirected away from guest routes', function () {
    $user = User::factory()->customer()->create();

    $this->actingAs($user)->get('/login')->assertRedirect('/');
    $this->actingAs($user)->get('/register')->assertRedirect('/');
});

test('authenticated user can logout and session is invalidated', function () {
    $user = User::factory()->customer()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});

test('middleware priority runs auth before role even if declared in reverse order', function () {
    Route::middleware(['web', EnsureUserHasRole::class.':admin', 'auth'])
        ->get('/_test-priority', fn () => 'ok');

    $this->get('/_test-priority')->assertRedirect('/login');
});
