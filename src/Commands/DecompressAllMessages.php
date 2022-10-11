<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Teamnovu\LaravelNotificationLog\Models\SentMessageLog;

class DecompressAllMessages extends Command
{
    protected $signature = 'notification-log:decompress-all';

    protected $description = 'Decompresses all messages in the database';

    public function handle()
    {
        $bar = $this->output->createProgressBar(SentMessageLog::count());

        $bar->start();

        // disable compression trait
        Config::set('notification-log.compress-messages', false);

        foreach (SentMessageLog::cursor() as $log) {
            /** @var SentMessageLog $log */
            if (is_compressed($log->body)) {
                $log->body = decompress_text($log->body);
                $log->save();
            }

            $bar->advance();
        }

        $bar->finish();

        return Command::SUCCESS;
    }
}
