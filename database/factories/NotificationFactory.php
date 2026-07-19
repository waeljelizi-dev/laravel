<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $type = fake()->randomElement([
            'comment',
            'like',
            'follow',
            'system',
        ]);

        return [
            'user_id' => User::factory(),
            'type'    => $type,
            'title'   => $this->titleFor($type),
            'body'    => fake()->sentence(),
            'data'    => [],
            'read_at' => fake()->boolean(40) ? now() : null,
        ];
    }

    public function unread(): static
    {
        return $this->state(fn () => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn () => [
            'read_at' => now(),
        ]);
    }

    private function titleFor(string $type): string
    {
        $name = fake()->name();

        return match ($type) {
            'comment' => "{$name} commented on your post",
            'like'    => "{$name} liked your post",
            'follow'  => "{$name} started following you",
            default   => 'System notification',
        };
    }
}
