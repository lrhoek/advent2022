<?php

$pairs = explode(PHP_EOL, file_get_contents('input'));
$pairs = array_map(fn ($pair) => array_map(fn ($elf) => range(...explode('-', $elf)), explode(',', $pair)), $pairs);

$contains = array_filter($pairs, fn ($pair) => empty(array_diff(...$pair)) || empty(array_diff(...array_reverse($pair))));
$overlaps = array_filter($pairs, fn ($pair) => !empty(array_intersect(...$pair)));

echo count($contains).PHP_EOL;
echo count($overlaps).PHP_EOL;