<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ── Known test accounts ────────────────────────────────
        // These are created with specific, predictable credentials
        // So you can always log in as them during development
        // admin@blog.com / password → admin access
        // editor@blog.com / password → editor access
        // user@blog.com / password → regular user

        User::factory()->admin()->create([
            'name'     => 'Admin User',
            'email'    => 'admin@blog.com',
            'password' => 'password', // plain — factory hashes it
        ]);

        User::factory()->editor()->create([
            'name'  => 'Editor User',
            'email' => 'editor@blog.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'user@blog.com',
            'password' => 'password',
        ]);

        // ── Random users ──────────────────────────────────────
        // 20 more users with random data
        // These give realistic variety — different names, emails, roles
        User::factory(20)->create();

        // Total: 23 users
        // 3 known (for login testing) + 20 random (for data variety)
    }
}
