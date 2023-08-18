<?php

namespace Teamnovu\LaravelNotificationLog\Contracts;

interface ShouldLogNotification
{
    public function getCurrentAttempt(): int;

    public function setCurrentAttempt(int $attempt = null): void;
}
