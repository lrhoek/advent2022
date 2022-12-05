<?php

function map($instruction) {
    preg_match('/(?<amount>\d+).+(?<from>\d+).+(?<to>\d+)/', $instruction, $map);
    return $map;
}

function move($stacks, $move) {
    $stacks[$move["to"] - 1][] = array_pop($stacks[$move["from"] - 1]);
    return $stacks;
}

function move9001($stacks, $move) {
    array_push($stacks[$move["to"] - 1], ...array_slice($stacks[$move["from"] - 1], -$move["amount"], $move["amount"]));
    array_splice($stacks[$move["from"] - 1], -$move["amount"]);
    return $stacks;
}

list($stacks, $instructions) = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));

$stacks = array_map(fn ($stack) => array_filter($stack, fn ($item) => !is_null($item) && ctype_upper($item)), array_map(null, ...array_map(fn ($line) => array_column(array_chunk(str_split($line), 4), 1), array_reverse(array_slice(explode(PHP_EOL, $stacks),0, -1)))));

$instructions = array_map(map(...), explode(PHP_EOL, $instructions));

/** @var array $stacks9000 */
$stacks9000 = array_reduce(array_merge(...array_map(fn ($instruction) => array_fill(0, $instruction["amount"], $instruction), $instructions)), move(...), $stacks);

/** @var array $stacks9001 */
$stacks9001 = array_reduce($instructions, move9001(...), $stacks);

echo join(array_map(fn ($stack) => end($stack), $stacks9000)).PHP_EOL;
echo join(array_map(fn ($stack) => end($stack), $stacks9001)).PHP_EOL;
