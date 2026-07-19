<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    //--Configuration---------------------------
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'data',
        'read_at',
    ];

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array
    {
        return [
            'data'    => 'array',     // JSON -> PHP array automatically
            'read_at' => 'datetime',
        ];
    }

    protected $appends = [
        'is_read',
        'is_unread',
        'time_ago',
        'icon',
    ];
    //--Accessors-------------------------------
    
    protected function isRead(): Attribute
    {
        return Attribute::make(
            get: fn() => !is_null($this->read_at),
        );
    }

    protected function isUnread(): Attribute
    {
        return Attribute::make(
            get: fn() => is_null($this->read_at),
        );
    }

      protected function timeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans(),
        );
    }

    // Icon based on type
    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn() => match($this->type) {
                'comment' => '💬',
                'like'    => '❤️',
                'follow'  => '👥',
                'system'  => '⚙️',
                default   => '🔔',
            },
        );
    }

    // Background color for unread state
    protected function bgColor(): Attribute
    {
        return Attribute::make(
            get: fn() => match($this->type) {
                'comment' => 'bg-blue-50',
                'like'    => 'bg-red-50',
                'follow'  => 'bg-green-50',
                'system'  => 'bg-yellow-50',
                default   => 'bg-white',
            },
        );
    }


    //--Relationships---------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //--Local Scopes----------------------------
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest()->take(10);
    }

    //--Business Logic--------------------------

    // Mark this notification as read
    public function markAsRead(): bool
    {
        if ($this->is_unread) {
            return $this->update(['read_at' => now()]);
        }
        return true;
    }

    // Mark this notification as unread
    public function markAsUnread(): bool
    {
        return $this->update(['read_at' => null]);
    }

     // Static helper — mark all of a user's notifications as read
    public static function markAllAsReadFor(User $user): int
    {
        return static::where('user_id', $user->id)
                     ->whereNull('read_at')
                     ->update(['read_at' => now()]);
        // Returns number of rows updated
    }

    // Static helper — create a notification for a user
    public static function notify(
        User   $user,
        string $type,
        string $title,
        string $body  = null,
        array  $data  = [],
    ): static {
        return static::create([
            'user_id' => $user->id,
            'type'    => $type,
            'title'   => $title,
            'body'    => $body,
            'data'    => $data,
        ]);
    }

}
