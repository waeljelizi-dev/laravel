<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Give every user 5 notifications (mix of read and unread)
        User::each(function (User $user) {
            Notification::factory(5)->create(['user_id' => $user->id]);
        });
    }
}
