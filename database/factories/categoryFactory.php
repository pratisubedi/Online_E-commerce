<?php


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class categoryFactory extends Factory
{
    //protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'status'=>rand(0,1),
            'slug'=>Str::slug(fake()->name(), '-')
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}