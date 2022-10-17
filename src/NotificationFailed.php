<?php

namespace Teamnovu\LaravelNotificationLog;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class NotificationFailed
{
    use Queueable, SerializesModels;

    public function __construct(
        public $notifiable,
        public $notification,
        public $channel,
        public $exception
    ) {
    }
}
