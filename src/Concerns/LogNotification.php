<?php

namespace Teamnovu\LaravelNotificationLog\Concerns;

trait LogNotification
{
    public static $attempt = 0;

    public function getCurrentAttempt(): int
    {
        return static::$attempt;
    }
}
