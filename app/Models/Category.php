<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Category;
class Category extends Model
{
    use HasFactory;

    //---Configuration---------------------------
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'sort_order',
        'is_visible',
    ];

     /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible'  => 'boolean',
            'sort_order'  => 'integer',
            'post_count'  => 'integer',
        ];
    }

    protected $appends = [
        'full_name',
    ];

    //---Route Model Binding --------------------------

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    //---lifecycle hooks--------------------------
    
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });
    }

    //---Accessors-------------------------------------------------

    // Full name includes parent: "Technology > Laravel"
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->parent
                ? $this->parent->name . ' › ' . $this->name
                : $this->name,
        );
    }
   

    //---Relationships---------------------------

     // Self-referential — parent category
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Self-referential — child categories
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
                    ->orderBy('sort_order');
    }

    // Recursive children — all descendants at any depth
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
        // Careful — this is recursive. Works for shallow trees (2-3 levels).
        // For deeply nested trees, use nested set or closure table packages.
    }

    // Posts in this category
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Only published posts
    public function publishedPosts(): HasMany
    {
        return $this->hasMany(Post::class)
                    ->where('status', 'published');
    }
    //---Local scopes---------------------------
    
   // Only top-level categories (no parent)
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    // Only visible
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    // Ordered by sort_order
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }


    //---Business logic----------------------------

    // Check if this is a top-level category
    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }

    // Check if this category has children
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    // Increment post count (called when a post is published)
    public function incrementPostCount(): void
    {
        $this->increment('post_count');
    }

    // Decrement post count (called when a post is unpublished/deleted)
    public function decrementPostCount(): void
    {
        $this->decrement('post_count');
    }
}
