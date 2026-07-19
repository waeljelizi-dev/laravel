<?php

namespace App\Models;


use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory; 
    use Notifiable; // enables $user->notify(new SomeNotification) 


    // Configuation -----------------------------
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'bio',
        'website',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',  // Laravel 10+ — auto-hashes on set
            'is_active'         => 'boolean',
            'role'              => UserRole::class,
        ];
    }

    protected $appends = [
        'avatar_url',
        'is_admin',
        'is_editor',
    ];



    //LifeCycle hooks--------------------
    protected static function booted(): void
    {
        // Auto-generate username from name if not provided
        static::creating(function (User $user) {
            if (empty($user->username)) {
                $base     = \Str::slug($user->name);
                $username = $base;
                $counter  = 1;

                // Keep trying until unique
                while (static::where('username', $username)->exists()) {
                    $username = $base . $counter++;
                }

                $user->username = $username;
            }
        });

        // When a user is deleted, clean up their avatar
        static::deleting(function (User $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        });
    }


    //Accessors --------------------------------
     // Avatar URL — real image or generated placeholder
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->avatar
                ? Storage::disk('public')->url($this->avatar)
                : "https://ui-avatars.com/api/?name={$this->name}&background=random",
        );
    }

    // Convenience booleans — avoids checking role everywhere
    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->role === UserRole::Admin,
        );
    }

    protected function isEditor(): Attribute
    {
        return Attribute::make(
            get: fn() => in_array($this->role, [UserRole::Admin, UserRole::Editor]),
        );
    }

    // Mutator — always lowercase and trim email
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtolower(trim($value)),
        );
    }

    //Relationships ---------------------------
    // Posts authored by this user
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

    // Comments made by this user
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // Notifications for this user
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Unread notifications only
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)
                    ->whereNull('read_at');
    }

    //local scopes-----------------------------
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', UserRole::Admin);
    }

    public function scopeEditors(Builder $query): Builder
    {
        return $query->whereIn('role', [UserRole::Admin, UserRole::Editor]);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function(Builder $q) use ($term) {
            $q->where('name',     'like', "%{$term}%")
              ->orWhere('email',    'like', "%{$term}%")
              ->orWhere('username', 'like', "%{$term}%");
        });
    }


    //business logic -------------------------------
    // Promote user to editor
    public function promoteToEditor(): bool
    {
        return $this->update(['role' => UserRole::Editor]);
    }

    // Promote user to admin
    public function promoteToAdmin(): bool
    {
        return $this->update(['role' => UserRole::Admin]);
    }

    // Deactivate account
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    // Reactivate account
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    // Check if user can moderate content
    public function canModerate(): bool
    {
        return $this->is_editor;
    }

    // Check if user owns a model
    public function owns(Model $model, string $foreignKey = 'user_id'): bool
    {
        return $this->id === $model->{$foreignKey};
    }
}
