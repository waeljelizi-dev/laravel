<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;
    
    //--Configuration--------------------------------
    protected $fillable = [
        'name',
        'slug',
        'color',
        'post_count',
    ];

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'post_count' => 'integer',
        ];
    }
    
    //--Route Model Binding -------------------------------
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    //--LifeCycle Hooks---------------------------
    protected static function booted(): void
    {
        static::creating(function (Tag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = \Str::slug($tag->name);
            }
        });
    }
    //--Relations-------------------------------------

    // Posts with this tag — many-to-many through post_tag pivot
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
                    ->withTimestamps(); // tracks when tag was added to post
    }

    // Only published posts with this tag
    public function publishedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
                    ->where('status', 'published')
                    ->withTimestamps();
    }

    //--Local scopes------------------------------------

    // Popular tags — most posts
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('post_count');
    }

    // Tags with at least one post
    public function scopeUsed(Builder $query): Builder
    {
        return $query->where('post_count', '>', 0);
    }

    //--Business Logic ---------------------------------

    public function incrementPostCount(): void
    {
        $this->increment('post_count');
    }

    public function decrementPostCount(): void
    {
        $this->decrement('post_count');
    }
}
