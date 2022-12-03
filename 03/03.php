<?php

function duplicates($backpack) {
    return array_values(array_intersect(...array_chunk($backpack, ceil(count($backpack)/2))))[0];
}

function priority($item) {
    return ctype_upper($item) ? ord($item) % 64 + 26: ord($item) % 96;
}

function badges($group) {
    return array_values(array_intersect(...$group))[0];
}

$backpacks = array_map(str_split(...), explode(PHP_EOL, file_get_contents('input')));
$duplicates = array_map(duplicates(...), $backpacks);
$badges = array_map(badges(...), array_chunk($backpacks, 3));

echo array_sum(array_map(priority(...), $duplicates)).PHP_EOL;
echo array_sum(array_map(priority(...), $badges)).PHP_EOL;