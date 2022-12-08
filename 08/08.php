<?php

function viewing_lines($patch, $row, $col) : array {
    return [
        array_slice($patch[$row], 0, $col + 1),
        array_reverse(array_slice($patch[$row], $col)),
        array_slice(array_column($patch, $col), 0, $row + 1),
        array_reverse(array_slice(array_column($patch, $col), $row))
    ];
}

function visible_in_line(bool $carry, $line) : bool {
    $last = array_pop($line);
    return $carry || empty($line) || $last > max($line);
}

function visible(array $patch, int $row, int $col) : bool {
    return array_reduce(viewing_lines($patch, $row, $col), visible_in_line(...), false);
}

function count_visible_trees_in_row($patch, $rowKey) : int {
    return count(array_filter(array_keys($patch[$rowKey]), fn ($colKey) => visible($patch, $rowKey, $colKey)));
}

function count_visible_trees_in_patch($patch) : int {
    return array_sum(array_map(fn ($rowKey) => count_visible_trees_in_row($patch, $rowKey), array_keys($patch)));
}

function viewing_distance($line) : int {
    $last = array_pop($line);
    $blockers = array_filter(array_keys($line), fn($key) => $line[$key] >= $last);
    return count($line) - end($blockers);
}

function scenic_score(array $patch, int $row, int $col) : int {
    return array_product(array_map(viewing_distance(...), viewing_lines($patch, $row, $col)));
}

function highest_scenic_score_in_row($patch, $rowKey) {
    return max(array_map(fn ($colKey) => scenic_score($patch, $rowKey, $colKey), array_keys($patch[$rowKey])));
}

function highest_scenic_score_in_patch($patch) {
    return max(array_map(fn ($rowKey) => highest_scenic_score_in_row($patch, $rowKey), array_keys($patch)));
}

$patch = array_map(str_split(...), explode(PHP_EOL, file_get_contents('input')));

echo count_visible_trees_in_patch($patch).PHP_EOL;
echo highest_scenic_score_in_patch($patch).PHP_EOL;

