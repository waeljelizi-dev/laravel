<?php

// app/Enums/UserRole.php

namespace App\Enums;

enum UserRole: string
{
    case User   = 'user';
    case Editor = 'editor';
    case Admin  = 'admin';

    public function label(): string
    {
        return match($this) {
            self::User   => 'User',
            self::Editor => 'Editor',
            self::Admin  => 'Administrator',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::User   => 'gray',
            self::Editor => 'blue',
            self::Admin  => 'red',
        };
    }

    public function canModerate(): bool
    {
        return in_array($this, [self::Editor, self::Admin]);
    }
}