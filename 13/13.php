<?php

function compare(array $left, array $right) {

    $current_left = array_shift($left);
    $current_right = array_shift($right);

    $current_left = is_array($current_right) && is_numeric($current_left) ? [$current_left] : $current_left;
    $current_right = is_array($current_left) && is_numeric($current_right) ? [$current_right] : $current_right;

    $current_left ??= -1;
    $current_right ??= -1;

    $result = is_array($current_left) && is_array($current_right) ? compare($current_left, $current_right) : $current_left <=> $current_right;

    return $result !== 0 || empty($left) && empty($right) ? $result : compare($left, $right);
}

$signals = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));
$signals = array_map(fn ($pair) => array_map(json_decode(...) ,explode(PHP_EOL, $pair)), $signals);

$pair_orders = array_map(fn ($pair) => compare($pair[0], $pair[1]), $signals);
$right_orders = array_keys($pair_orders, -1);

echo array_sum($right_orders) + count($right_orders).PHP_EOL;

$packets = array_merge(...$signals);
array_push($packets, [[2]], [[6]]);
usort($packets, compare(...));

$two = array_search([[2]], $packets);
$six = array_search([[6]], $packets);

echo ($two + 1) * ($six + 1).PHP_EOL;