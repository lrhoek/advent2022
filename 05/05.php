<?php

function map_instruction($instruction) {
    preg_match('/(?<amount>\d+).+(?<from>\d+).+(?<to>\d+)/', $instruction, $map);
    return $map;
}

function filter_stack($stack) {
    return array_filter($stack, fn($item) => !is_null($item) && ctype_upper($item));
}

function extract_items($row) {
    return array_column(array_chunk(str_split($row), 4), 1);
}

function move9000($stacks, $move) {
    array_push($stacks[$move["to"] - 1], ...array_reverse(array_splice($stacks[$move["from"] - 1], -$move["amount"])));
    return $stacks;
}

function move9001($stacks, $move) {
    array_push($stacks[$move["to"] - 1], ...array_splice($stacks[$move["from"] - 1], -$move["amount"]));
    return $stacks;
}

function transpose($stacks) {
    return array_map(null, ...extract_rows($stacks));
}

function extract_rows($stacks) {
    return array_map(extract_items(...), array_reverse(array_slice(explode(PHP_EOL, $stacks),0, -1)));
}

list($stacks, $instructions) = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));

$stacks = array_map(filter_stack(...), transpose($stacks));
$instructions = array_map(map_instruction(...), explode(PHP_EOL, $instructions));

/** @var array $stacks9000 */
$stacks9000 = array_reduce($instructions, move9000(...), $stacks);

/** @var array $stacks9001 */
$stacks9001 = array_reduce($instructions, move9001(...), $stacks);

echo join(array_map(fn ($stack) => end($stack), $stacks9000)).PHP_EOL;
echo join(array_map(fn ($stack) => end($stack), $stacks9001)).PHP_EOL;
