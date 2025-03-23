<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static int $counter = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uni_code' => 'PU' . str_pad(self::$counter++, 5, '0', STR_PAD_LEFT),
            'user_group_id' => '3',
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('09########'), // Số điện thoại dạng 09xxxxxxxx
            'gender' => fake()->randomElement(['male', 'female']),
            'birthday' => fake()->date('Y-m-d', '-20 years'), // Sinh nhật cách đây ít nhất 20 năm
            'address' => fake()->address(),
            'phone_verified_at' => fake()->optional()->dateTime(),
            'email_verified_at' => now(),
            'password' => '$2y$10$t0lozVN4x1chTLot/mzs/u3qnftSctvpsCUjEv5AO2yfgfiIXI9IG', // Mật khẩu mặc định
            'remember_token' => Str::random(10),
            'published' => fake()->boolean(90), // 90% người dùng được kích hoạt
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
