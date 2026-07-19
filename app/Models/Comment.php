<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use HasFactory;
    //--Configuration-----------------------------
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'body',
        'is_approved',
        'likes_count',
        'ip_address',
    ];

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'is_approved' => 'boolean',
            'likes_count' => 'integer',
            'deleted_at'  => 'datetime',
        ];
    }

    protected $appends = [
        'is_reply',
        'time_ago',
    ];
    //--Lifecycle hooks---------------------------

    protected static function booted(): void
    {
        // When a comment is approved, increment the post's comment count
        static::updated(function (Comment $comment) {
            if ($comment->isDirty('is_approved')) {
                if ($comment->is_approved) {
                    $comment->post->increment('comments_count');
                } else {
                    $comment->post->decrement('comments_count');
                }
            }
        });

        // When a comment is deleted, fix the post count
        static::deleted(function (Comment $comment) {
            if ($comment->is_approved) {
                $comment->post->decrement('comments_count');
            }
        });
    }

    //--Accessors----------------------------------

    // Is this a reply to another comment?
    protected function isReply(): Attribute
    {
        return Attribute::make(
            get: fn() => !is_null($this->parent_id),
        );
    }

    // Human-readable time — "3 hours ago"
    protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans(),
        );
    }


    //--Relationships------------------------------

    //the post this comment belongs to
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // The user who wrote this comment
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Parent comment (if this is a reply)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Replies to this comment
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->where('is_approved', true)
                    ->oldest();
    }

    // All replies including nested (recursive)
    public function allReplies(): HasMany
    {
        return $this->replies()->with('allReplies');
    }
    //--Local Scopes-----------------------------------
    
    // Only approved comments
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    // Only pending (not yet approved)
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }

    // Only top-level (not replies)
    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    // Only replies
    public function scopeReplies(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    //--Business Logic-------------------------------------
    
    // Approve this comment
    public function approve(): bool
    {
        return $this->update(['is_approved' => true]);
    }

     // Reject and soft delete
    public function reject(): bool
    {
        $this->update(['is_approved' => false]);
        return $this->delete();
    }
    
    // Check if user owns this comment
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    // Check if user can delete this comment
    public function canBeDeletedBy(User $user): bool
    {
        return $this->isOwnedBy($user) || $user->is_admin;
    }
}
