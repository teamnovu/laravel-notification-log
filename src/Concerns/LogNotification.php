<?php

namespace Teamnovu\LaravelNotificationLog\Concerns;

trait LogNotification
{
    public static $attempt = [];

    public function getCurrentAttempt(): int
    {
        if ($this->id) {
            return self::$attempt[$this->id] ?? 0;
        }

        return 0;
    }

    public function setCurrentAttempt(?int $attempt = null): void
    {
        if ($attempt === null) {
            $attempt = $this->getCurrentAttempt() + 1;
        }

        ray(self::$attempt, $this->id);

        self::$attempt[$this->id] = $attempt;
    }
}
