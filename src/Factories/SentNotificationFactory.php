<?php

namespace Teamnovu\LaravelNotificationLog\Factories;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Arr;
use Teamnovu\LaravelNotificationLog\Models\SentNotificationLog;

class SentNotificationFactory
{
    public function createFromSentNotification(NotificationSent $event)
    {
        $notification = SentNotificationLog::make([
            'notification_id' => $event->notification->id,
            'notification' => get_class($event->notification),
            'notifiable' => $this->formatNotifiable($event->notifiable),
            'queued' => in_array(ShouldQueue::class, class_implements($event->notification)),
            'channel' => $event->channel,
            'response' => $event->response,
        ]);

        return $notification;
    }

    /**
     * Format the given notifiable into a tag.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    private function formatNotifiable($notifiable): string
    {
        if ($notifiable instanceof Model) {
            return get_class($notifiable).':'.implode('_', Arr::wrap($notifiable->getKey()));
        }

        if ($notifiable instanceof AnonymousNotifiable) {
            $routes = array_map(function ($route) {
                return is_array($route) ? implode(',', $route) : $route;
            }, $notifiable->routes);

            return 'Anonymous:'.implode(',', $routes);
        }

        return get_class($notifiable);
    }
}
