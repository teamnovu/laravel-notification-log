<?php

namespace Teamnovu\LaravelNotificationLog\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class CompressedText implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  Model  $model
     * @param  mixed  $value
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return decompress_text($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  Model  $model
     * @param  mixed  $value
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return compress_text($value);
    }
}
