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
       Schema::create('post_tag', function (Blueprint $table) {
        // Pivot tables have NO id() by default
        // Just the two foreign keys

        $table->foreignId('post_id')
              ->constrained()
              ->cascadeOnDelete();
        // Post deleted → remove its tag rows from pivot

        $table->foreignId('tag_id')
              ->constrained()
              ->cascadeOnDelete();
        // Tag deleted → remove its post rows from pivot

        // Composite primary key — prevents duplicate combinations
        $table->primary(['post_id', 'tag_id']);

        // Optional: extra columns on the pivot
        $table->timestamp('tagged_at')->useCurrent();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
