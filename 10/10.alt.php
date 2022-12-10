<?php

function execute($state, $instruction) {
    return array_reduce(array_map(intval(...), explode(" ", $instruction)), cycle(...), $state);
}

function cycle($state, $delta = 0) {
    $x = end($state["x"]);
    $position = (count($state["x"]) - 1) % 40;
    $state["crt"][] = [...end($state["crt"]), $position => in_array($position, range($x - 1, $x + 1)) ? "#" : "."];
    $state["x"][] = $x + $delta;
    return $state;
}

$state = array_reduce(explode(PHP_EOL, file_get_contents('input')), execute(...), ["x" => [1], "crt" => [array_fill(0, 40, '.')]]);

echo array_sum(array_map(fn ($cycle) => $cycle * $state["x"][$cycle-1], range(20, count($state["x"]), 40))).PHP_EOL;
echo join(PHP_EOL, array_map(fn ($cycle) => join("", $state["crt"][$cycle]), range(40, count($state["x"]), 40))).PHP_EOL;