<?php

$elves = explode(PHP_EOL.PHP_EOL, file_get_contents('input'));
$elves = array_map(fn ($elf) => array_sum(explode(PHP_EOL, $elf)), $elves);
rsort($elves);

echo reset($elves).PHP_EOL;
echo array_sum(array_slice($elves, 0, 3)).PHP_EOL;