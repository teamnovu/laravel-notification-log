<?php

namespace Teamnovu\LaravelNotificationLog;

use App\Console\Commands\CompressAllMessages;
use App\Console\Commands\DecompressAllMessages;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Teamnovu\LaravelNotificationLog\Listeners\MessageEventListener;

class LaravelNotificationLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-notification-log')
            ->hasConfigFile()
            //->hasViews()
            ->hasMigrations(['create_sent_notification_logs_table', 'create_sent_message_logs_table'])
            ->hasCommands([
                CompressAllMessages::class,
                DecompressAllMessages::class,
            ]);
    }

    public function packageBooted()
    {
        Event::subscribe(MessageEventListener::class);

        $existingCallback = Mailable::$viewDataCallback;

        Mailable::buildViewDataUsing(function ($mailable) use ($existingCallback) {
            $existingData = $existingCallback ? call_user_func($existingCallback, $mailable) : [];

            return array_merge($existingData, [
                '__laravel_notification_log_mailable' => get_class($mailable),
                '__laravel_notification_log_queued' => in_array(ShouldQueue::class, class_implements($mailable)),
            ]);
        });
    }
}
