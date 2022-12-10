<?php

function cycle($state) {

    $instruction = array_shift($state["instructions"]);
    $x = end($state["x"]);

    $sprite = [$x-1, $x, $x+1];
    $position = (count($state["x"]) - 1) % 40;

    $crt = end($state["crt"]);
    $crt[$position] = in_array($position, $sprite) ? "#" : ".";
    $state["crt"][] = $crt;

    $state["x"][] = $x + ($instruction[0] === "addx" && $state["working"] ? (int) $instruction[1] : 0);
    $state["working"] = $instruction[0] === "addx" && !$state["working"];

    $requeue = $state["working"] ? [$instruction] : [];
    array_unshift($state["instructions"], ...$requeue);

    return count($state["instructions"]) ? cycle($state) : $state;
}

function run($instructions) {
    return cycle(["instructions" => $instructions, "x" => [1], "working" => false, "crt" => [array_fill(0, 40, ".")]]);
}

$instructions = array_map(fn ($instruction) => explode(" ", $instruction), explode(PHP_EOL, file_get_contents('input')));
$result = run($instructions);

echo array_sum(array_map(fn ($cycle) => $cycle * $result["x"][$cycle-1], range(20, 220, 40))).PHP_EOL;
echo join(PHP_EOL, array_map(fn ($cycle) => join("", $result["crt"][$cycle]), range(40, 240, 40))).PHP_EOL;