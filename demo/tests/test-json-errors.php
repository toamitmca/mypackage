<?php

$json = [];

// Products JSON
$json['products.json'] = file_get_contents(__DIR__ . '/../src/data/products.json');

// Distributors JSON
$json['distributors.json'] = file_get_contents(__DIR__ . '/../src/data/distributors.json');

// Check JSON for errors
foreach ($json as $key => $string) {
    echo PHP_EOL . 'Decoding: ' . $key;
    json_decode($string);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
            break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        default:
            echo ' - Unknown error';
    }
    echo PHP_EOL;
}
