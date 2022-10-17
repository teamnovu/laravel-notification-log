<?php

it('can detect a base 64 encoded text', function () {
    $text = 'This is a test';
    $encoded = base64_encode($text);

    expect(is_compressed($encoded))->toBeTrue()
        ->and(is_compressed($text))->toBeFalse();
});

it('can compress and decompress text', function () {
    $text = 'This is a test';
    $compressed = compress_text($text);

    expect(is_compressed($compressed))->toBeTrue()
        ->and(decompress_text($compressed))->toBe($text);
});
