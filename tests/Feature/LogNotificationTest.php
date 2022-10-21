<?php

use Teamnovu\LaravelNotificationLog\Tests\Support\DummyNotification;

it('can get the current attempt', function () {
    $notification = new DummyNotification();

    expect($notification->getCurrentAttempt())->toBe(0);
});

it('can change the current attempt', function () {
    $notification = new DummyNotification();
    $notification2 = new DummyNotification();

    expect($notification->getCurrentAttempt())->toBe(0)
        ->and($notification2->getCurrentAttempt())->toBe(0);

    $notification->setCurrentAttempt();
    $notification2->setCurrentAttempt(10);

    expect($notification->getCurrentAttempt())->toBe(1)
        ->and($notification2->getCurrentAttempt())->toBe(10);
});
