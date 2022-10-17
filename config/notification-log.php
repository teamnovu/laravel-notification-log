<?php

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
