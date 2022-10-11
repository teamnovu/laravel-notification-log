<?php

namespace Teamnovu\LaravelNotificationLog\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Teamnovu\LaravelNotificationLog\Models\SentMessageLog;

class CompressAllMessages extends Command
{
    protected $signature = 'notification-log:compress-all';

    protected $description = 'Compresses all messages in the database';

    public function handle()
    {
        $bar = $this->output->createProgressBar(SentMessageLog::count());

        $bar->start();

        // disable compression trait
        Config::set('notification-log.compress-messages', false);

        foreach (SentMessageLog::cursor() as $log) {
            /** @var SentMessageLog $log */
            if (! is_compressed($log->body)) {
                $log->body = compress_text($log->body);
                $log->save();
            }

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
