<?php

function move($positions, $direction) {
    $head = end($positions[0]);

    $head[0] = $direction === "D" ? $head[0] - 1 : ($direction === "U" ? $head[0] + 1 : $head[0]);
    $head[1] = $direction === "L" ? $head[1] - 1 : ($direction === "R" ? $head[1] + 1 : $head[1]);
    $positions[0][] = $head;

    return array_reduce(range(0, count($positions)-2), follow(...), $positions);
}

function follow($positions, $lead) {
    $diff = [
        end($positions[$lead])[0] - end($positions[$lead+1])[0],
        end($positions[$lead])[1] - end($positions[$lead+1])[1]
    ];

    $next = end($positions[$lead+1]);
    $move = abs($diff[0]) === 2 || abs($diff[1]) === 2;

    $positions[$lead+1][] = $move ? [$next[0] + ($diff[0] <=> 0), $next[1] + ($diff[1] <=> 0)] : $next;

    return $positions;
}

function instruction($positions, $instruction) {
    list ($direction, $amount) = explode(" ", $instruction);
    return array_reduce(array_fill(0, $amount, $direction), move(...), $positions);
}

function apply($instructions) {
    return array_reduce($instructions, instruction(...), array_fill(0, 10, [[0,0]]));
}

$instructions = explode(PHP_EOL, $instructions = file_get_contents('input'));
$positions = apply($instructions);

echo count(array_unique($positions[1], SORT_REGULAR)).PHP_EOL;
echo count(array_unique($positions[9], SORT_REGULAR)).PHP_EOL;