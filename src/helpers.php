<?php

if (! function_exists('is_compressed')) {
    function is_compressed($data)
    {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return true;
        }

        return false;
    }
}

if (! function_exists('compress_text')) {
    function compress_text($text)
    {
        return base64_encode(gzcompress($text, 9));
    }
}

if (! function_exists('decompress_text')) {
    function decompress_text($text)
    {
        return gzuncompress(base64_decode($text));
    }
}
