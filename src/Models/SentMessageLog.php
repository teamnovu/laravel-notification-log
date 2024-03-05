<?php

namespace Teamnovu\LaravelNotificationLog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Teamnovu\LaravelNotificationLog\Concerns\CompressesBody;

class SentMessageLog extends Model
{
    use CompressesBody;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'mailable' => 'array',
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
        'sender' => 'array',
        'reply_to' => 'array',
        'headers' => 'array',
        'attachments' => 'array',
        'sent_at' => 'datetime',
        'queued' => 'boolean',
    ];
}
