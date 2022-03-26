<?php

namespace Wallbox\Models;

class Cardinal
{
    const INVALID_CARDINAL = 'INVALID_CARDINAL';

    const NORTH = 'N';
    const SOUTH = 'S';
    const WEST  = 'W';
    const EAST  = 'E';

    const CARDINALS = [
        self::NORTH,
        self::EAST,
        self::SOUTH,
        self::WEST,
    ];

    protected $current;

    public function __construct(string $cardinal)
    {
        if (!in_array($cardinal, self::CARDINALS)) throw new \Exception(self::INVALID_CARDINAL);
        $this->current = array_search($cardinal, self::CARDINALS);
    }

    public function toString():string
    {
        return self::CARDINALS[$this->current];
    }

    public function moveRight():Cardinal
    {
        return new Cardinal(self::CARDINALS[++$this->current % count(self::CARDINALS)]);
    }

    public function moveLeft():Cardinal
    {
        if ($this->current == 0) {
            $this->current = count(self::CARDINALS);
        }
        return new Cardinal(self::CARDINALS[--$this->current]);
    }
}