<?php

namespace Teamnovu\LaravelNotificationLog\Notifications;

use Illuminate\Notifications\NotificationSender as BaseNotificationSender;
use Teamnovu\LaravelNotificationLog\NotificationFailed;
use Throwable;

class NotificationSender extends BaseNotificationSender
{
    /**
     * Send the given notification to the given notifiable via a channel.
     *
     * @param  mixed  $notifiable
     * @param  string  $id
     * @param  mixed  $notification
     * @param  string  $channel
     * @return void
     */
    protected function sendToNotifiable($notifiable, $id, $notification, $channel)
    {
        try {
            parent::sendToNotifiable($notifiable, $id, $notification, $channel);
        } catch (Throwable $ex) {
            $this->events->dispatch(
                new NotificationFailed($notifiable, $notification, $channel, $ex)
            );

            throw $ex;
        }
    }
}
