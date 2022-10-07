<?php

namespace Teamnovu\LaravelNotificationLog\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Teamnovu\LaravelNotificationLog\LaravelNotificationLog
 */
class LaravelNotificationLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Teamnovu\LaravelNotificationLog\LaravelNotificationLog::class;
    }
}
