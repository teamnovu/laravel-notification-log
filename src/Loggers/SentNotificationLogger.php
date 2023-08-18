<?php

namespace Teamnovu\LaravelNotificationLog\Loggers;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Teamnovu\LaravelNotificationLog\Contracts\ShouldLogNotification;
use Teamnovu\LaravelNotificationLog\Models\SentNotificationLog;
use Teamnovu\LaravelNotificationLog\NotificationFailed;

class SentNotificationLogger
{
    public function logSendingNotification(NotificationSending $event): ?SentNotificationLog
    {
        if (! $event->notification instanceof ShouldLogNotification) {
            return null;
        }

        $currentAttempt = SentNotificationLog::query()
            ->where('notification_id', $event->notification->id)
            ->where('channel', $event->channel)
            ->max('attempt');

        // when you retry a job after it failed after several tries, the attempt of the instance will be reset as
        // it will be pushed as a new instance with the same notification id to the queue.
        // Therefor we need to increment the attempt manually by looking it up in the logs table.
        if ($currentAttempt > $event->notification->getCurrentAttempt()) {
            $event->notification->setCurrentAttempt($currentAttempt + 1);
        } else {
            $event->notification->setCurrentAttempt();
        }

        /** @var SentNotificationLog $notification */
        $notification = SentNotificationLog::updateOrCreate([
            'notification_id' => $event->notification->id,
            'channel' => $event->channel,
            'attempt' => $event->notification->getCurrentAttempt(),
        ], [
            'notification' => get_class($event->notification),
            'notifiable' => $this->formatNotifiable($event->notifiable),
            'queued' => in_array(ShouldQueue::class, class_implements($event->notification)),
            'message' => $this->resolveMessage($event->channel, $event->notification, $event->notifiable),
            'status' => 'sending',
        ]);

        return $notification;
    }

    public function logSentNotification(NotificationSent $event): ?SentNotificationLog
    {
        if (! $event->notification instanceof ShouldLogNotification) {
            return null;
        }

        /** @var SentNotificationLog $notification */
        $notification = SentNotificationLog::updateOrCreate([
            'notification_id' => $event->notification->id,
            'channel' => $event->channel,
            'attempt' => $event->notification->getCurrentAttempt(),
        ], [
            'notification' => get_class($event->notification),
            'notifiable' => $this->formatNotifiable($event->notifiable),
            'queued' => in_array(ShouldQueue::class, class_implements($event->notification)),
            'response' => $this->formatResponse($event->response),
            'status' => 'sent',
        ]);

        return $notification;
    }

    public function logFailedNotification(NotificationFailed $event): ?SentNotificationLog
    {
        if (! $event->notification instanceof ShouldLogNotification) {
            return null;
        }

        /** @var SentNotificationLog $notification */
        $notification = SentNotificationLog::updateOrCreate([
            'notification_id' => $event->notification->id,
            'channel' => $event->channel,
            'attempt' => $event->notification->getCurrentAttempt(),
        ], [
            'response' => $event->exception,
            'status' => 'error',
        ]);

        return $notification;
    }

    public function resolveMessage(string $channel, Notification $notification, $notifiable)
    {
        if (! config('notification-log.resolve-notification-message')) {
            return null;
        }

        $channelManager = resolve(ChannelManager::class);
        $channel = $channelManager->driver($channel);

        // we never want to save the mail message here, as it will be logged by the sent mail logger.
        if ($channel instanceof MailChannel) {
            return null;
        }

        try {
            // it the only channel anonymous notifiables are used
            if ($channel instanceof \Illuminate\Notifications\Channels\VonageSmsChannel) {
                $message = $notification->toVonage($notifiable);

                if (is_string($message)) {
                    $message = new \Illuminate\Notifications\Messages\VonageMessage($message);
                }

                return $message->content;
            }

            if ($notifiable instanceof AnonymousNotifiable) {
                return null;
            }

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
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Format the given notifiable into a tag.
     *
     * @param  mixed  $notifiable
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

    protected function formatResponse($response): mixed
    {
        if (is_string($response)) {
            return $response;
        }

        if (is_object($response) && method_exists($response, 'toArray')) {
            return json_encode($response->toArray());
        }

        return json_encode($response);
    }
}
