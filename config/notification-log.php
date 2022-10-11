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
];
