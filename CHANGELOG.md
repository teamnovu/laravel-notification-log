# Changelog

All notable changes to `laravel-notification-log` will be documented in this file.

## v2.0.0 - 2023-08-18

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.3.3...v2.0.0

## v1.3.3 - 2022-12-12

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.3.2...v1.3.3

## v1.3.2 - 2022-10-21

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.3.1...v1.3.2

## v1.3.1 - 2022-10-17

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.3.0...v1.3.1

## v1.3.0 - 2022-10-17

### Breaking Change

You now need to apply the following Interface and Trait so a Notification can be logged:

```php
use Teamnovu\LaravelNotificationLog\Concerns\LogNotification;
use Teamnovu\LaravelNotificationLog\Contracts\ShouldLogNotification;

class DummyNotification extends Notification implements ShouldLogNotification
{
    use LogNotification;

   // ...
}





```
**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.2.0...v1.3.0

## v1.2.0 - 2022-10-17

### What's Changed

- Add way to resolve messages of notifications for several Notification Channels like sms, telegram, webpush and so on... by @okaufmann in https://github.com/teamnovu/laravel-notification-log/pull/1

### New Contributors

- @okaufmann made their first contribution in https://github.com/teamnovu/laravel-notification-log/pull/1

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.1.4...v1.2.0

## v1.1.4 - 2022-10-12

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.1.3...v1.1.4

## v1.1.3 - 2022-10-11

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.1.2...v1.1.3

## v1.1.2 - 2022-10-11

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.1.1...v1.1.2

## v1.1.1 - 2022-10-11

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.1.0...v1.1.1

## Add possibility to compress message bodies to reduce storage - 2022-10-11

**Full Changelog**: https://github.com/teamnovu/laravel-notification-log/compare/v1.0.0...v1.1.0
