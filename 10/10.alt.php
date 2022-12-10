<?php

function init() {
    $state["line"] = [];
    $state["strength"] = [];
    $state["x"] = [1];
    $state["crt"] = array_fill(0, 40, '.');

    return $state;
}

function execute($state, $instruction) {
    $deltas = array_map(intval(...), explode(" ", $instruction));
    return array_reduce($deltas, cycle(...), $state);
}

function cycle($state, $delta) {
    $x = end($state["x"]);
    $cycle = count($state["x"]);

    $position = ($cycle - 1) % 40;
    $strength = ($cycle - 20) % 40 === 0 ? [$cycle => $cycle * $x] : [];
    $line = ($cycle + 1) % 40 === 0 ? [join("", $state["crt"])] : [];
    $pixel = in_array($position, range($x - 1, $x + 1)) ? "#" : ".";

    $state["crt"][$position] = $pixel;
    $state["x"][] = $x + $delta;
    array_push($state["strength"], ...$strength);
    array_push($state["line"], ...$line);

    return $state;
}

$instructions = explode(PHP_EOL, file_get_contents('input'));
$state = array_reduce($instructions, execute(...), init());

echo array_sum($state["strength"]).PHP_EOL;
echo join(PHP_EOL, $state["line"]).PHP_EOL;
