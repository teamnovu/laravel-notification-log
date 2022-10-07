<?php

namespace Teamnovu\LaravelNotificationLog\Commands;

use Illuminate\Console\Command;

class LaravelNotificationLogCommand extends Command
{
    public $signature = 'laravel-notification-log';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
