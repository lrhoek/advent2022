<?php

function populate($filesystem, $instructions, $path = []) {
    $instruction = explode(" ", array_shift($instructions));
    list($filesystem, $path) = array_shift($instruction)($filesystem, $path, $instruction);
    return empty($instructions) ? $filesystem : populate($filesystem, $instructions, $path);
}

function _IN($filesystem, $path, $instruction) {
    $path[] = $instruction[0];
    return [$filesystem, $path];
}

function _OUT($filesystem, $path) {
    array_pop($path);
    return [$filesystem, $path];
}

function _FILE($filesystem, $path, $instruction) {
    $filesystem[join(':', $path)][$instruction[0]] = (int) $instruction[1];
    return [$filesystem, $path];
}

function _DIR($filesystem, $path, $instruction) {
    $filesystem[join(':', $path)][$instruction[0]] = join(':', $path).":".$instruction[0];
    return [$filesystem, $path];
}

function dirsize($filesystem, $directory) {
    return array_reduce(
        $filesystem[$directory],
        fn ($total, $item) => $total + (is_int($item) ? $item : dirsize($filesystem, $item)),
        0
    );
}

$instructions = preg_replace(
    ['/\$ cd \.\./', '/\$ cd ([a-z.\/]+)/', '/\$ ls\n/', '/dir ([a-z.]+)/', '/(\d+) ([a-z.]+)/'],
    ['_OUT', '_IN $1', '', '_DIR $1', '_FILE $2 $1'],
    file_get_contents('input')
);

$filesystem = populate([], explode(PHP_EOL, $instructions));
$dirsizes = array_map(fn ($directory) => dirsize($filesystem, $directory), array_keys($filesystem));
echo array_sum(array_filter($dirsizes, fn ($size) => $size <= 100000)).PHP_EOL;

$required =  dirsize($filesystem, '/') - 40000000;
echo min(array_filter($dirsizes, fn ($size) => $size >= $required)).PHP_EOL;