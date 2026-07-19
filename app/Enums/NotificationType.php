<?php

namespace App\Enums;

enum NotificationType: string
{
    case Comment = 'comment';
    case Like    = 'like';
    case Follow  = 'follow';
    case System  = 'system';
    
    public function icon(): string
    {
        return match($this) {
            self::Comment => '💬',
            self::Like    => '❤️',
            self::Follow  => '👥',
            self::System  => '⚙️',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Comment => 'Comment',
            self::Like    => 'Like',
            self::Follow  => 'Follow',
            self::System  => 'System',
        };
    }
}