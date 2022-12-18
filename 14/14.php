<?php

function addpoints($cave, $point) {
    $cave[$point[0].":".$point[1]] = true;
    return $cave;
}

function map($cave, $path) {

    $start = array_shift($path);
    $end = reset($path);

    $start = explode(",", $start);
    $end = explode(",", $end);

    $vertical_difference = abs($start[0] - $end[0]);
    $horizontal_difference = abs($start[1] - $end[1]);

    $horizontal_line = array_map(null, array_fill(0, $horizontal_difference + 1, (int) $start[0]), range($start[1], $end[1]));
    $vertical_line = array_map(null, range($start[0], $end[0]), array_fill(0, $vertical_difference + 1, (int) $start[1]));

    $cave = array_reduce($horizontal_line, addpoints(...), $cave);
    $cave = array_reduce($vertical_line, addpoints(...), $cave);

    return count($path) > 1 ? map($cave, $path) : $cave;
}

function sand(&$cave, $bottom = null, $count = 0, $position = "500:0") {

    list($x, $y) = array_map(intval(...), explode(":", $position));

    $below = $x . ":" . $y + 1;
    $left = $x - 1 . ":" . $y + 1;
    $right = $x + 1 . ":" . $y + 1;

    $filled_below = isset($cave[$below]);
    $filled_left = isset($cave[$left]);
    $filled_right = isset($cave[$right]);

    !($filled_below && $filled_left && $filled_right) || $cave[$x . ":" . $y] = true;

    $next = null;
    $next = ($filled_below && $filled_left && !$filled_right) ? $right : $next;
    $next = ($filled_below && !$filled_left) ? $left : $next;
    $next = (!$filled_below) ? $below : $next;

    $count += (int) is_null($next);
    $next ??= "500:0";

    $result1 = $y > $bottom;
    $result2 = $position === "500:0" && $filled_below && $filled_left && $filled_right;

    $result = is_null($bottom) ? $result2 : $result1;

    return $result ? $count : sand($cave, $bottom, $count, $next);
}

$paths = explode(PHP_EOL, file_get_contents('input'));
$paths = array_map(fn ($path) => explode(" -> ", $path), $paths);
$cave = array_reduce($paths, map(...), []);
$bottom = array_reduce(array_keys($cave), fn ($lowest, $point) => max($lowest, explode(":", $point)[1]), 0);

echo sand($cave, $bottom).PHP_EOL;

$cave = array_reduce($paths, map(...), []);
$floor = array_map(null, range(0, 999), array_fill(0, 1000, $bottom + 2));
$cave = array_reduce($floor, addpoints(...), $cave);

echo sand($cave).PHP_EOL;