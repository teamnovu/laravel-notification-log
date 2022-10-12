<?php

namespace Teamnovu\LaravelNotificationLog\Loggers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Teamnovu\LaravelNotificationLog\Models\SentNotificationLog;

class SentNotificationLogger
{
    public function logSendingNotification(NotificationSending $event)
    {
        $notification = SentNotificationLog::create([
            'notification_id' => $event->notification->id,
            'notification' => get_class($event->notification),
            'notifiable' => $this->formatNotifiable($event->notifiable),
            'queued' => in_array(ShouldQueue::class, class_implements($event->notification)),
            'channel' => $event->channel,
            'message' => $this->resolveMessage($event->channel, $event->notification, $event->notifiable),
            'status' => 'sending',
        ]);

        return $notification;
    }

    public function logSentNotification(NotificationSent $event)
    {
        $notification = SentNotificationLog::updateOrCreate([
            'notification_id' => $event->notification->id,
        ], [
            'notification' => get_class($event->notification),
            'notifiable' => $this->formatNotifiable($event->notifiable),
            'queued' => in_array(ShouldQueue::class, class_implements($event->notification)),
            'channel' => $event->channel,
            'response' => $event->response,
            'status' => 'sent',
        ]);

        return $notification;
    }

    public function resolveMessage(string $channel, Notification $notification, $notifiable)
    {
        // TODO
        if ($notifiable instanceof AnonymousNotifiable) {
            return null;
        }

        $channelManager = resolve(ChannelManager::class);
        $channel = $channelManager->driver($channel);

        if ($channel instanceof \NotificationChannels\Telegram\TelegramChannel) {
            $message = $notification->toTelegram($notifiable);
            if (is_string($message)) {
                $message = \NotificationChannels\Telegram\TelegramMessage::create($message);
            }

            return $message->toArray();
        }

        if ($channel instanceof \NotificationChannels\WebPush\WebPushChannel) {
            $message = $notification->toWebPush($notifiable, $notification);

            return $message->toArray();
        }

        if ($channel instanceof \Illuminate\Notifications\Channels\VonageSmsChannel) {
            $message = $notification->toVonage($notifiable);

            if (is_string($message)) {
                $message = new \Illuminate\Notifications\Messages\VonageMessage($message);
            }

            return $message->content;
        }

        if ($channel instanceof DatabaseChannel) {
            if (method_exists($notification, 'toDatabase')) {
                return is_array($data = $notification->toDatabase($notifiable))
                    ? $data : null;
            }

            if (method_exists($notification, 'toArray')) {
                return $notification->toArray($notifiable);
            }
        }

        return null;
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
