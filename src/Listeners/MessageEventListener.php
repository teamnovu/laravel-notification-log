<?php

namespace Teamnovu\LaravelNotificationLog\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;
use Teamnovu\LaravelNotificationLog\Factories\SentMessageFactory;
use Teamnovu\LaravelNotificationLog\Factories\SentNotificationFactory;

class MessageEventListener
{
    public function handleSentNotification(NotificationSent $event)
    {
        resolve(SentNotificationFactory::class)->createFromSentNotification($event)->save();
    }

    public function handleSentMail(MessageSent $event)
    {
        resolve(SentMessageFactory::class)->createFromSentMessage($event)->save();
    }

    public function subscribe($events)
    {
        $events->listen(NotificationSent::class, [self::class, 'handleSentNotification']);
        $events->listen(MessageSent::class, [self::class, 'handleSentMail']);
    }
}
