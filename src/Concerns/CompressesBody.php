<?php

namespace Teamnovu\LaravelNotificationLog\Concerns;

use Teamnovu\LaravelNotificationLog\Casts\CompressedText;

trait CompressesBody
{
    public function getCasts()
    {
        if (config('notification-log.compress-messages', false)) {
            return array_merge($this->casts, [
                'body' => CompressedText::class,
            ]);
        }

        return parent::getCasts();
    }
}
