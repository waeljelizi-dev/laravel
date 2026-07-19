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
        Schema::create('posts', function (Blueprint $table) {
            //--- Identify
            $table->id();
            //Foreign keys
            $table->foreignId('user_id')
                    ->constrained()
                    ->cascadeOnDelete();
            //If user deleted -> deleted their posts too
            $table->foreignId('category_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            //if category deleted - set posts.category_id to null
            
            //---Content---------------
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt',500)->nullable();
            $table->longText('body');
            
            //---Media---------
            $table->string('cover_image')->nullable();
            $table->string('cover_image_alt')->nullable();

            //----Status & visibility
            $table->enum('status',[
                'draft',
                'pending',
                'published',
                'rejected',
                'scheduled'
            ])->default('draft');

            $table->boolean('is_featured')->default(false);   
            $table->boolean('allow_comments')->default(true);

            //---Metrics-------------------------
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedSmallInteger('reading_time')->default(1); //minutes

            //--SEO-----------------------------------------------
            $table->string('meta_title')->nullable();
            $table->string('meta_description',500)->nullable();
            $table->json('meta')->nullable(); //flexible extra data

            //--Dates
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();

            //---Moderation
            $table->foreignId('approved_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('reject_reason')->nullable();

            //---Standard-------------------------
            $table->timestamps();// create at + updated_at
            $table->softDeletes(); //deleted_at (soft delete)

            //Indexes
            //Add indexes on columns you filter or sort by frequently
            $table->index('status');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index(['user_id', 'status']); //composite
            $table->index(['status','published_at']); //composite - for homepage  query
            $table->index(['category_id', 'status']); //composite - for category page

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
