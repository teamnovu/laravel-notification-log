<?php

namespace Teamnovu\LaravelNotificationLog\Tests\Support;

use Illuminate\Notifications\Notification;
use Ramsey\Uuid\Uuid;
use Teamnovu\LaravelNotificationLog\Concerns\LogNotification;
use Teamnovu\LaravelNotificationLog\Contracts\ShouldLogNotification;

class DummyNotification extends Notification implements ShouldLogNotification
{
    use LogNotification;

    public function __construct()
    {
        $this->id = (string) Uuid::uuid4();
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
