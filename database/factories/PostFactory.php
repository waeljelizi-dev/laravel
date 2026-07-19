<?php

namespace Database\Factories;

use App\Models\Post;
use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate title first — slug depends on it
        $title = fake()->sentence(rand(4, 8));
        // rand(4,8) means 4 to 8 words — more natural variation
        // sentence() ends with a period — rtrim removes it
        $title = rtrim($title, '.');

        return [
            // ── Foreign Keys ─────────────────────────────────

            // User::factory() means:
            // "if no user_id is provided when calling this factory,
            //  automatically create a new User and use its ID here"
            // This lets you create a Post standalone without
            // worrying about its author
            'user_id'     => User::factory(),

            // Same pattern for category
            'category_id' => Category::factory(),

            // ── Content ──────────────────────────────────────

            'title'  => $title,

            // Str::slug converts 'Hello World' → 'hello-world'
            // Every post gets a unique URL-safe slug
            'slug'   => Str::slug($title),

            // Two sentences as a short excerpt
            'excerpt' => fake()->paragraph(2),

            // implode joins array of paragraphs into one string
            // separated by double newlines (like markdown)
            // rand(5,10) = 5 to 10 paragraphs
            'body' => implode("\n\n", fake()->paragraphs(rand(5, 10))),

            // ── Status ───────────────────────────────────────

            // Default state is published — most useful for testing
            // Overridden by states like draft() or pending()
            'status' => PostStatus::Published,

            // 20% chance of being featured
            // fake()->boolean(20) = true 20% of the time
            'is_featured' => fake()->boolean(20),

            // ── Metrics ──────────────────────────────────────

            // Random view count — makes the UI look realistic
            'views_count' => fake()->numberBetween(0, 10000),

            // Published date in the past year
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    //States------------------------------------
    //state() accepts a closure or array
    //it mergers with definition() - only the fields you specify change
    //everything elese stay the same as definition()

    public function draft(): static
    {
        // A draft post:
        // - status = draft
        // - published_at = null (not published yet)
        // Everything else (title, body, etc.) stays as definition() defined
        return $this->state([
            'status'       => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        // A featured post must also be published
        // State can override multiple fields
        return $this->state([
            'status'      => PostStatus::Published,
            'is_featured' => true,
        ]);
    }

    public function published(): static
    {
        return $this->state([
            'status'       => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function pending(): static
    {
        // Pending = waiting for admin approval
        return $this->state([
            'status'       => PostStatus::Pending,
            'published_at' => null,
        ]);
    }

    //State with a closure - for computed values
    public function popular(): static
    {
        return $this->state(function (array $attributes) {
            // $attributes = the definition() values already resolved
            // You can reference them here
            return [
                'views_count' => fake()->numberBetween(10000, 500000),
                'status'      => PostStatus::Published,
                // title stays whatever definition() gave it
            ];
        });
    }

}
