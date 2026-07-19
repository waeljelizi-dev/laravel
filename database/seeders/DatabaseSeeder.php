<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // call() runs seeders in sequence — one after another
        // ORDER MATTERS — foreign keys must exist before they're referenced
        $this->call([
            UserSeeder::class,     // ← must be first, everything references users
            CategorySeeder::class, // ← posts need categories
            TagSeeder::class,      // ← posts need tags
            PostSeeder::class,     // ← needs users + categories to exist
            CommentSeeder::class,  // ← needs posts + users to exist
            NotificationSeeder::class, // ← needs users to exist
        ]);
    }
}
