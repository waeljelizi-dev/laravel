<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ── Load existing records into memory ──────────────────
        // We load these ONCE and reuse them
        // Without this, every post would trigger new user/category creation
        $users      = User::all();
        $categories = Category::all();
        $tags       = Tag::all();

        // ── Featured posts ─────────────────────────────────────
        // create() returns a Collection — so we can chain ->each()
        // ->each() loops over every created post and runs the callback
        Post::factory(5)
            ->featured()
            ->recycle($users)      // pick random users from $users
            ->recycle($categories) // pick random categories
            ->create()
            ->each(function (Post $post) use ($tags) {
                // $tags->random(rand(2,4)) picks 2-4 random Tag models
                // ->pluck('id') converts to an array of IDs: [3, 7, 12]
                // ->attach() inserts rows into the post_tag pivot table
                $post->tags()->attach(
                    $tags->random(rand(2, 4))->pluck('id')
                );
            });

        // ── Published posts ────────────────────────────────────
        Post::factory(30)
            ->published()
            ->recycle($users)
            ->recycle($categories)
            ->create()
            ->each(fn($post) =>
                $post->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')
                )
            );

        // ── Draft posts ────────────────────────────────────────
        // for() links every post to one specific user
        // Here we use the editor account we created in UserSeeder
        $editor = User::where('email', 'editor@blog.com')->first();

        Post::factory(10)
            ->draft()
            ->for($editor, 'author') // 'author' = relationship name on Post
            ->recycle($categories)
            ->create();
            // Draft posts don't get tags — fine for testing

        // Final counts: 5 featured + 30 published + 10 draft = 45 posts
    }
}
