<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{


    /**
     * Common programming-related tags
     */
     private array $tags = [
        'PHP',
        'Laravel',
        'JavaScript',
        'Vue.js',
        'React',
        'MySQL',
        'Redis',
        'Docker',
        'API',
        'Testing',
        'Security',
        'Performance',
        'Deployment',
        'CSS',
        'TailwindCSS',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement($this->tags)
            . ' '
            . fake()->word();

        return [
            'name'  => $name,
            'slug'  => Str::slug($name),
            'color' => fake()->safeHexColor(),
            'post_count' => 0,
        ];
    }
}
