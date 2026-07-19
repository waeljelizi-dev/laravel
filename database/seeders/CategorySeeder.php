<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Predefined categories — more realistic than random words
        $categories = [
            ['name' => 'Technology',   'color' => '#378ADD'],
            ['name' => 'Laravel',      'color' => '#E24B4A'],
            ['name' => 'JavaScript',   'color' => '#EF9F27'],
            ['name' => 'DevOps',       'color' => '#534AB7'],
            ['name' => 'Tutorials',    'color' => '#1D9E75'],
            ['name' => 'Career',       'color' => '#888780'],
        ];

        foreach ($categories as $data) {
            Category::create([
                ...$data,
                'slug'       => \Str::slug($data['name']),
                'is_visible' => true,
            ]);
        }
    }
}
