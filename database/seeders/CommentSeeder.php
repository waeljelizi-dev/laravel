<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $users = User::all();

        // Post::published() uses the scope we defined on the model
        // ->each() loops through every published post
        Post::published()->each(function (Post $post) use ($users) {

            // For EACH published post, create 3 to 7 comments
            Comment::factory(rand(3, 7))
                ->for($post)     // every comment belongs to this post
                ->recycle($users) // random authors from existing users
                ->create();

            // Result: ~140-245 comments total across 35 published posts
        });

        // Note: draft posts get no comments — realistic
        // Note: all comments are approved by default (factory default)
    }
}
