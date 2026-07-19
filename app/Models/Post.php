<?php

// app/Models/Post.php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    // ── Configuration ──────────────────────────────────────────

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt',
        'body', 'cover_image', 'cover_image_alt', 'status',
        'is_featured', 'allow_comments', 'views_count', 'likes_count',
        'comments_count', 'reading_time', 'meta_title',
        'meta_description', 'meta', 'published_at', 'scheduled_at',
        'approved_by', 'approved_at', 'reject_reason',
    ];

    protected $casts = [
        'is_featured'    => 'boolean',
        'allow_comments' => 'boolean',
        'views_count'    => 'integer',
        'likes_count'    => 'integer',
        'comments_count' => 'integer',
        'reading_time'   => 'integer',
        'meta'           => 'array',
        'status'         => PostStatus::class,
        'published_at'   => 'datetime',
        'scheduled_at'   => 'datetime',
        'approved_at'    => 'datetime',
        'deleted_at'     => 'datetime',
    ];

    protected $hidden = [
        'reject_reason', // internal moderation data
    ];

    protected $appends = [
        'reading_time_label',
        'cover_image_url',
        'excerpt_or_body',
        'is_published',
    ];

    // ── Route Model Binding ─────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Lifecycle Hooks ─────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->user_id) && auth()->check()) {
                $post->user_id = auth()->id();
            }
        });

        static::saving(function (Post $post) {
            if ($post->isDirty('body')) {
                $words = str_word_count(strip_tags($post->body));
                $post->reading_time = max(1, (int) ceil($words / 200));
            }
            if ($post->isDirty('status')
                && $post->status === PostStatus::Published
                && is_null($post->published_at)) {
                $post->published_at = now();
            }
        });

        static::deleting(function (Post $post) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
        });
    }

    // ── Accessors ──────────────────────────────────────────────

    protected function readingTimeLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->reading_time . ' min read',
        );
    }

    protected function coverImageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cover_image
                ? Storage::disk('public')->url($this->cover_image)
                : asset('images/placeholder.jpg'),
        );
    }

    protected function excerptOrBody(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->excerpt
                ?? Str::limit(strip_tags($this->body), 200),
        );
    }

    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === PostStatus::Published
                      && $this->published_at?->isPast(),
        );
    }

    // ── Local Scopes ───────────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', PostStatus::Published)
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', PostStatus::Draft);
    }

    public function scopeForCategory(Builder $query, int|Category $category): Builder
    {
        $id = $category instanceof Category ? $category->id : $category;
        return $query->where('category_id', $id);
    }

    public function scopeForAuthor(Builder $query, int|User $author): Builder
    {
        $id = $author instanceof User ? $author->id : $author;
        return $query->where('user_id', $id);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function(Builder $q) use ($term) {
            $q->where('title',   'like', "%{$term}%")
              ->orWhere('body',   'like', "%{$term}%")
              ->orWhere('excerpt','like', "%{$term}%");
        });
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->orderByDesc('views_count');
    }

    // ── Relationships (covered in Lesson 2.4) ─────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedComments(): HasMany
    {
    return $this->hasMany(Comment::class)
                ->where('is_approved', true);
    }   

    // ── Business Logic Methods ─────────────────────────────────

    public function publish(): bool
    {
        return $this->update(['status' => PostStatus::Published]);
    }

    public function unpublish(): bool
    {
        return $this->update([
            'status'       => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function approve(User $approver): bool
    {
        return $this->update([
            'status'      => PostStatus::Published,
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);
    }

    public function reject(string $reason, User $reviewer): bool
    {
        return $this->update([
            'status'        => PostStatus::Rejected,
            'reject_reason' => $reason,
            'approved_by'   => $reviewer->id,
            'approved_at'   => now(),
        ]);
    }

    public function recordView(): void
    {
        $this->increment('views_count');
    }

    public function canBeEditedBy(User $user): bool
    {
        return $user->id === $this->user_id
            || in_array($user->role, ['admin', 'editor']);
    }
}