<?php

namespace Teamnovu\LaravelNotificationLog;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Teamnovu\LaravelNotificationLog\Commands\LaravelNotificationLogCommand;

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
            ->hasViews()
            ->hasMigration('create_laravel-notification-log_table')
            ->hasCommand(LaravelNotificationLogCommand::class);
    }
}
