<?php

namespace Teamnovu\LaravelNotificationLog\Tests\Support;

use Illuminate\Notifications\Notification;

class DummyFailingNotification extends Notification
{
    public function __construct()
    {
        $this->id = '1234567890';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        throw new \Exception('Notification could not be sent!');

        return [
            'message' => 'Consectetur culpa ex aliquip ex anim.',
        ];
    }
}
