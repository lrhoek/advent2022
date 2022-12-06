<?php

function find_first_unique_sequence($string, $length) : string {
    $current = substr($string, 0, $length);
    return strlen(count_chars($current, 3)) === $length ? $current : find_first_unique_sequence(substr($string, 1), $length);
}

function find_marker($string, $length) : int {
    return strpos($string, find_first_unique_sequence($string, $length)) + $length;
}

$input = file_get_contents('input');

echo find_marker($input, 4).PHP_EOL;
echo find_marker($input, 14).PHP_EOL;