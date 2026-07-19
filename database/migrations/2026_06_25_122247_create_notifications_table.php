<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type');
            // e.g 'comment', 'like', 'follow', 'system'

            $table->string('title');
            $table->string('body')->nullable();
            $table->json('data')->nullable();//Flexible - store IDs, urls, extra context

            $table->timestamp('read_at')->nullable();
            //null = unread ; has value - read

            $table->timestamps();

            $table->index(['user_id','read_at']);
            //Optimises: Where user_id = ? and read_at is NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
