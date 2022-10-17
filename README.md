# Laravel Notification Log

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teamnovu/laravel-notification-log.svg?style=flat-square)](https://packagist.org/packages/teamnovu/laravel-notification-log)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/teamnovu/laravel-notification-log/run-tests?label=tests)](https://github.com/teamnovu/laravel-notification-log/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/teamnovu/laravel-notification-log/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/teamnovu/laravel-notification-log/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/teamnovu/laravel-notification-log.svg?style=flat-square)](https://packagist.org/packages/teamnovu/laravel-notification-log)

Logs every sent Notification and Mail of your entire Laravel Project.

## Installation

You can install the package via composer:

```bash
composer require teamnovu/laravel-notification-log
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="notification-log-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="notification-log-config"
```

The following config file will be published in config/notification-log.php:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Compress Messages
    |--------------------------------------------------------------------------
    |
    | In case you send a lot of E-Mails the message_sent_logs table could become
    | very big. With this option you can enable that the body of every log
    | entry will be compressed with gzip to reduce its size.
    |
    */

    'compress-messages' => env('NOTIFICATION_LOG_COMPRESS_MESSAGES', false),
    
    /*
    |--------------------------------------------------------------------------
    | Resolve Notification Message
    |--------------------------------------------------------------------------
    |
    | If this is enabled, the Logger will try to resolve the built message
    | out of the notification. This is useful if you want to debug your
    | sent notifications.
    |
    */

    'resolve-notification-message' => env('NOTIFICATION_LOG_RESOLVE_NOTIFICATION_MESSAGE', false),
];
```

## Usage

Add the following Interface and Trait to your Notification:

```php
use Teamnovu\LaravelNotificationLog\Concerns\LogNotification;
use Teamnovu\LaravelNotificationLog\Contracts\ShouldLogNotification;

class DummyNotification extends Notification implements ShouldLogNotification
{
    use LogNotification;

    // ...
    
}
```

Now send a Notification or Mail as you would normally do. The package will automatically log the Notification or Mail.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

[//]: # (## Contributing)

[//]: # ()
[//]: # (Please see [CONTRIBUTING]&#40;CONTRIBUTING.md&#41; for details.)

## Credits

- [Oliver Kaufmann](https://github.com/teamnovu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
