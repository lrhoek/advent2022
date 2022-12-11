<?php

class Troop {

    public array $monkeys = [];
    public int $divisor;
    public bool $use_troop_divisor = false;

    public function __construct($input) {
        $monkeyData = explode(PHP_EOL.PHP_EOL, $input);

        array_walk($monkeyData, $this->addMonkey(...));

        $divisors = array_map(fn (Monkey $monkey) => $monkey->divisor, $this->monkeys);
        $this->divisor = array_product($divisors);
    }

    private function addMonkey(string $monkey) {
        preg_match_all("/\d+|\*|\+|old/", $monkey, $items);
        $id = array_shift($items[0]);
        $targets[false] = (int) array_pop($items[0]);
        $targets[true] = (int) array_pop($items[0]);
        $divisor = (int) array_pop($items[0]);
        $operand = array_pop($items[0]);
        $operator = array_pop($items[0]);
        array_pop($items[0]);
        $items = $items[0];

        $monkey = new Monkey($id, $items, $divisor, $targets, $operand, $operator, $this);
        $this->monkeys[$monkey->id] = $monkey;
    }

    public function round() {
        array_walk($this->monkeys, fn ($monkey) => $monkey->turn());
    }

    public function rounds($amount) {
        $this->use_troop_divisor = $amount > 20;
        $range = range(1, $amount);
        array_walk($range, $this->round(...));
    }

    public function monkey_business() {
        $activity = array_map(fn (Monkey $monkey) => $monkey->inspections, $this->monkeys);
        rsort($activity);
        return array_product(array_slice($activity, 0, 2));
    }
}


class Monkey {
    public int $inspections = 0;

    public function __construct(
        public int $id,
        public array $items,
        public int $divisor,
        private array $targets,
        private string $operand,
        private string $operator,
        private Troop $troop
    ) {}

    private function test($item) {
        return $item % $this->divisor === 0;
    }

    private function inspect($item) {
        $this->inspections++;
        $item = $this->operation($item);

        $item = $this->troop->use_troop_divisor ? $item % $this->troop->divisor : intdiv($item, 3);

        $target = $this->targets[$this->test($item)];
        $this->troop->monkeys[$target]->items[] = $item;
    }

    private function operation($item) {
        $operand = $this->operand === "old" ? $item : $this->operand;
        return $this->operator === "+" ? $item + $operand : $item * $operand;
    }

    public function turn() {
        array_walk($this->items, $this->inspect(...));
        $this->items = [];
    }
}

$troop = new Troop(file_get_contents('input'));
$troop->rounds(20);
echo $troop->monkey_business().PHP_EOL;

$troop = new Troop(file_get_contents('input'));
$troop->rounds(10000);
echo $troop->monkey_business().PHP_EOL;