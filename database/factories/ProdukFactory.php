<?php

namespace Database\Factories;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->staff(),
            'nama' => fake()->words(3, true),
            'sku' => strtoupper(fake()->unique()->bothify('PRD-###??')),
            'merek' => fake()->company(),
            'kategori' => fake()->randomElement(['Fashion', 'Elektronik', 'Makanan', 'Kerajinan', 'Lainnya']),
            'berat' => fake()->numberBetween(100, 2000),
            'warna' => fake()->safeColorName(),
            'ukuran' => fake()->randomElement(['S', 'M', 'L', 'XL', '-']),
            'stok' => fake()->numberBetween(0, 50),
            'harga' => fake()->numberBetween(15000, 500000),
            'foto' => null,
            'is_locked' => false,
        ];
    }

    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_locked' => true,
        ]);
    }

    public function ownedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
