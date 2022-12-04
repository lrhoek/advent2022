<?php

$pairs = explode(PHP_EOL, file_get_contents('input'));
$pairs = array_map(fn ($pair) => array_merge(...array_map(fn ($elf) => explode('-', $elf), explode(',', $pair))), $pairs);

$contains = array_filter($pairs, fn ($pair) => $pair[0] <= $pair[2] && $pair[1] >= $pair[3] || $pair[2] <= $pair[0] && $pair[3] >= $pair[1]);
$overlaps = array_filter($pairs, fn ($pair) => $pair[0] <= $pair[3] && $pair[1] >= $pair[2]);

echo count($contains).PHP_EOL;
echo count($overlaps).PHP_EOL;