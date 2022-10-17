<?php

namespace Teamnovu\LaravelNotificationLog\Contracts;

interface ShouldLogNotification
{
    public function getCurrentAttempt(): int;
}
