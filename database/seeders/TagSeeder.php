<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Predefined tags — more realistic than random words
        $tags = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js',
            'MySQL', 'Redis', 'Docker', 'API',
            'Testing', 'Security', 'Performance', 'CSS',
        ];

        foreach ($tags as $name) {
            Tag::create([
                'name'  => $name,
                'slug'  => \Str::slug($name),
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'post_count' => 0,
            ]);
        }
    }
}
