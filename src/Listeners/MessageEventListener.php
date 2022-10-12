<?php

namespace Teamnovu\LaravelNotificationLog\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Teamnovu\LaravelNotificationLog\Loggers\SentMessageLogger;
use Teamnovu\LaravelNotificationLog\Loggers\SentNotificationLogger;

class MessageEventListener
{
    public function handleSentNotification(NotificationSent $event)
    {
        resolve(SentNotificationLogger::class)->logSentNotification($event);
    }

    public function handleSendingNotification(NotificationSending $event)
    {
        if (config('notification-log.resolve-notification-message')) {
            resolve(SentNotificationLogger::class)->logSendingNotification($event);
        }
    }

    public function handleSentMail(MessageSent $event)
    {
        resolve(SentMessageLogger::class)->logSentMessage($event);
    }

    public function subscribe($events)
    {
        $events->listen(NotificationSent::class, [self::class, 'handleSentNotification']);
        $events->listen(NotificationSending::class, [self::class, 'handleSendingNotification']);
        $events->listen(MessageSent::class, [self::class, 'handleSentMail']);
    }
}
