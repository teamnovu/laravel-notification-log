<?php

namespace Teamnovu\LaravelNotificationLog\Tests\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DummyNotifiable extends Model
{
    use Notifiable;

    public function getKey()
    {
        return '1234567890';
    }
}
