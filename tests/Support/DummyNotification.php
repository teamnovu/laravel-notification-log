<?php

namespace Teamnovu\LaravelNotificationLog\Tests\Support;

use Illuminate\Notifications\Notification;

class DummyNotification extends Notification
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
        return [
            'message' => 'Consectetur culpa ex aliquip ex anim.',
        ];
    }
}
