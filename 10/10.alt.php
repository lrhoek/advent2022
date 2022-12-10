<?php

function init() {
    $state["cycle"] = 0;
    $state["crt"] = array_fill(0, 40, '.');
    $state["lines"] = [];
    $state["strengths"] = [];
    $state["x"] = 1;

    return $state;
}

function execute($state, $instructions) {
    $deltas = array_map(intval(...), preg_split("/\s/ ", $instructions));
    return array_reduce($deltas, cycle(...), $state);
}

function cycle($state, $delta) {
    $state["cycle"]++;
    $state["crt"] = update_crt($state["crt"], $state["cycle"], $state["x"]);
    $state["lines"] = add_line($state["lines"], $state["cycle"], $state["crt"]);
    $state["strengths"] = add_strength($state["strengths"], $state["cycle"], $state["x"]);
    $state["x"] += $delta;

    return $state;
}

function update_crt($crt, $cycle, $x) {
    $position = ($cycle - 1) % 40;
    $pixel = in_array($position, range($x - 1, $x + 1)) ? "#" : ".";
    $crt[$position] = $pixel;
    return $crt;
}

function add_line($lines, $cycle, $crt) {
    $line = $cycle % 40 === 0 ? [join("", $crt)] : [];
    array_push($lines, ...$line);
    return $lines;
}

function add_strength($strengths, $cycle, $x) {
    $strength = ($cycle - 20) % 40 === 0 ? [$cycle => $cycle * $x] : [];
    array_push($strengths, ...$strength);
    return $strengths;
}

$instructions = file_get_contents('input');
$state = execute(init(), $instructions);

echo array_sum($state["strengths"]).PHP_EOL;
echo join(PHP_EOL, $state["lines"]).PHP_EOL;