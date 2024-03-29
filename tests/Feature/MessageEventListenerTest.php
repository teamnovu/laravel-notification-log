<?php

use Illuminate\Notifications\Events\NotificationSending;
use Teamnovu\LaravelNotificationLog\Listeners\MessageEventListener;
use Teamnovu\LaravelNotificationLog\Tests\Support\DummyNotifiable;
use Teamnovu\LaravelNotificationLog\Tests\Support\DummyNotification;

use function Pest\Laravel\assertDatabaseCount;

it('can does not log a sending notification message when disabled in configuration', function () {
    $notifiable = new DummyNotifiable();
    $notification = new DummyNotification();
    $listener = new MessageEventListener();

    config(['notification-log.resolve-notification-message' => false]);
    $listener->handleSendingNotification(new NotificationSending($notifiable, $notification, 'database'));

    assertDatabaseCount('sent_notification_logs', 1);
});

it('can does log a sending notification event when enabled in configuration', function () {
    $notifiable = new DummyNotifiable();
    $notification = new DummyNotification();
    $listener = new MessageEventListener();

    config(['notification-log.resolve-notification-message' => true]);
    $listener->handleSendingNotification(new NotificationSending($notifiable, $notification, 'database'));

    assertDatabaseCount('sent_notification_logs', 1);
});
