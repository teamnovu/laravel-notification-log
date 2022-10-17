<?php

namespace Teamnovu\LaravelNotificationLog\Tests\Support;

use Illuminate\Notifications\Notification;
use Teamnovu\LaravelNotificationLog\Concerns\LogNotification;
use Teamnovu\LaravelNotificationLog\Contracts\ShouldLogNotification;

class DummyFailingNotification extends Notification implements ShouldLogNotification
{
    use LogNotification;

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
