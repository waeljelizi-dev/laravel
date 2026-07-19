<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id'     => Post::factory(),
            'user_id'     => User::factory(),
            'body'        => fake()->paragraph(),
            'is_approved' => true,
            'ip_address'  => fake()->ipv4(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'is_approved' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'is_approved' => false,
        ]);
    }
}
