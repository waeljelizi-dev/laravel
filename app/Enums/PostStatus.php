<?php

// app/Enums/PostStatus.php
namespace App\Enums;

enum PostStatus: string
{
    case Draft     = 'draft';
    case Pending   = 'pending';
    case Published = 'published';
    case Rejected  = 'rejected';
    case Scheduled = 'scheduled';

    // Custom methods on the enum
    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Draft',
            self::Pending   => 'Awaiting Review',
            self::Published => 'Published',
            self::Rejected  => 'Rejected',
            self::Scheduled => 'Scheduled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft     => 'gray',
            self::Pending   => 'yellow',
            self::Published => 'green',
            self::Rejected  => 'red',
            self::Scheduled => 'blue',
        };
    }

    public function isPublic(): bool
    {
        return $this === self::Published;
    }
}