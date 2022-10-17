<?php

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Arr;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use Teamnovu\LaravelNotificationLog\Loggers\SentNotificationLogger;
use Teamnovu\LaravelNotificationLog\Tests\Support\DummyFailingNotification;
use Teamnovu\LaravelNotificationLog\Tests\Support\DummyNotifiable;
use Teamnovu\LaravelNotificationLog\Tests\Support\DummyNotification;

it('can log a sending notification event', function () {
    $notifiable = new DummyNotifiable();
    $notification = new DummyNotification();

    $logger = new SentNotificationLogger();
    config(['notification-log.resolve-notification-message' => true]);
    $log = $logger->logSendingNotification(new NotificationSending($notifiable, $notification, 'database'));

    expect($log->notification_id)->toBe($notification->id);
    expect($log->notification)->toBe(get_class($notification));
    expect($log->notifiable)->toBe(get_class($notifiable).':'.implode('_', Arr::wrap($notifiable->getKey())));
    expect($log->queued)->toBeFalse();
    expect($log->channel)->toBe('database');
    expect($log->message)->toBe(['message' => 'Consectetur culpa ex aliquip ex anim.']);
    expect($log->status)->toBe('sending');
    expect($log->attempt)->toBe(1);
});

it('can log a sending notification without message when disabled', function () {
    $notifiable = new DummyNotifiable();
    $notification = new DummyNotification();

    $logger = new SentNotificationLogger();
    config(['notification-log.resolve-notification-message' => false]);
    $log = $logger->logSendingNotification(new NotificationSending($notifiable, $notification, 'database'));

    expect($log->notification_id)->toBe($notification->id);
    expect($log->notification)->toBe(get_class($notification));
    expect($log->notifiable)->toBe(get_class($notifiable).':'.implode('_', Arr::wrap($notifiable->getKey())));
    expect($log->queued)->toBeFalse();
    expect($log->channel)->toBe('database');
    expect($log->message)->toBe(null);
    expect($log->status)->toBe('sending');
    expect($log->attempt)->toBe(1);
});

it('can update a notification once it is sent', function () {
    $notifiable = new DummyNotifiable();
    $notification = new DummyNotification();

    $logger = new SentNotificationLogger();
    config(['notification-log.resolve-notification-message' => true]);
    $logger->logSendingNotification(new NotificationSending($notifiable, $notification, 'database'));

    $logger->logSentNotification(new NotificationSent($notifiable, $notification, 'database', 'dummy response'));

    assertDatabaseCount('sent_notification_logs', 1);
    assertDatabaseHas('sent_notification_logs', [
        'notification_id' => $notification->id,
        'notification' => get_class($notification),
        'notifiable' => get_class($notifiable).':'.implode('_', Arr::wrap($notifiable->getKey())),
        'queued' => false,
        'channel' => 'database',
        'message' => json_encode(['message' => 'Consectetur culpa ex aliquip ex anim.']),
        'response' => 'dummy response',
        'status' => 'sent',
        'attempt' => 1,
    ]);
});

it('can log a failed notification', function () {
    $notifiable = new DummyNotifiable();
    $notification = new DummyFailingNotification();

    try {
        $notifiable->notify($notification);
    } catch (\Exception $e) {
    }

    assertDatabaseCount('sent_notification_logs', 1);
    assertDatabaseHas('sent_notification_logs', [
        'notification_id' => $notification->id,
        'notification' => get_class($notification),
        'notifiable' => get_class($notifiable).':'.implode('_', Arr::wrap($notifiable->getKey())),
        'queued' => false,
        'channel' => 'database',
        'message' => null,
        'status' => 'error',
        'response' => $e,
        'attempt' => 1,
    ]);
});
