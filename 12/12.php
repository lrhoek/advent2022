<?php

function find($grid, $value) {
    return array_merge(...array_map(fn ($row, $x) => array_map(fn ($y) => [$x, $y], array_keys($row, $value)), $grid, array_keys($grid)));
}

function neighbours($grid, $point) {
    list ($x, $y) = $point;
    $potential_neighbours = [[$x - 1, $y], [$x + 1, $y], [$x, $y - 1], [$x, $y + 1]];
    return array_filter($potential_neighbours, fn ($neighbour) => isset($grid[$neighbour[0]][$neighbour[1]]));
}

function reachable($grid, $source, $target) {
    list ($sX, $sY) = $source;
    list ($tX, $tY) = $target;
    return
        abs(ord($grid[$sX][$sY]) - ord($grid[$tX][$tY])) < 2 ||
        ord($grid[$sX][$sY]) > ord($grid[$tX][$tY]) && ctype_lower($grid[$sX][$sY]) && ctype_lower($grid[$tX][$tY])||
        $grid[$sX][$sY] === "S" && $grid[$tX][$tY] === "a" ||
        $grid[$sX][$sY] === "z" && $grid[$tX][$tY] === "E";
}

function shortest_path($grid, $visited, $paths, $target) {
    usort($paths, fn ($a, $b) => $a["d"] <=> $b["d"]);
    $shortest = array_shift($paths);
    $current = $shortest["c"];
    $paths = array_filter($paths, fn ($point) => $point["c"] !== $current);
    $visited[$current[0].":".$current[1]] = $shortest;
    $neighbours = neighbours($grid, $current);
    $unvisited_neighbours = array_filter($neighbours, fn ($neighbour) => !isset($visited[$neighbour[0].":".$neighbour[1]]));
    $reachable = array_filter($unvisited_neighbours, fn ($neighbour) => reachable($grid, $current, $neighbour));
    $newpaths = array_map(fn ($neighbour) => ["c" => $neighbour, "p" => $current, "d" => $shortest["d"] + 1], $reachable);
    array_push($paths, ...$newpaths);
    return ($current === $target) ? $shortest : shortest_path($grid, $visited, $paths, $target);
}

$grid = array_map(str_split(...), explode(PHP_EOL, file_get_contents('input')));

$S = find($grid, "S")[0];
$E = find($grid, "E")[0];
echo shortest_path($grid, [], [["c" => $S, "d" => 0, "p" => null]], $E)["d"].PHP_EOL;

$paths = array_map(fn ($point) => ["c" => $point, "d" => 0, "p" => null], find($grid, "a"));
echo shortest_path($grid, [], $paths, $E)["d"].PHP_EOL;