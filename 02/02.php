<?php

function value($challenge) {
    return [ord($challenge[0]) - ord("A"), ord($challenge[2]) - ord("X")];
}

function score($challenge) {
    // PHP deals... differently with modulo over negative numbers
    return ($challenge[1] - $challenge[0] + 4) % 3 * 3 + 1 + $challenge[1];
}

function score2($challenge) {
    return ($challenge[1] + $challenge[0] + 2) % 3 + 3 * $challenge[1] + 1;
}

$challenges = array_map(value(...), explode(PHP_EOL, file_get_contents('input')));

echo array_sum(array_map(score(...), $challenges)).PHP_EOL;
echo array_sum(array_map(score2(...), $challenges)).PHP_EOL;