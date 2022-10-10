<?php

namespace Teamnovu\LaravelNotificationLog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentNotificationLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'queued' => 'boolean',
    ];
}
