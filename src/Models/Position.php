<?php

namespace Wallbox\Models;

use Wallbox\Models\Cardinal as Cardinal;

class Position
{
    protected $x;
    protected $y;
    protected $cardinal;

    public function __construct(int $x, int $y, Cardinal $cardinal)
    {
        $this->x = $x;
        $this->y = $y;
        $this->cardinal = $cardinal;
    }

    public function getX():int
    {
        return $this->x;
    }

    public function getY():int
    {
        return $this->y;
    }

    public function getCardinal():Cardinal
    {
        return $this->cardinal;
    }

    public function forward():Position
    {

        if ($this->cardinal->toString() == Cardinal::NORTH) {
            return new Position($this->x, $this->y + 1, $this->cardinal);
        }

        if ($this->cardinal->toString() == Cardinal::SOUTH) {
            return new Position($this->x, $this->y - 1, $this->cardinal);
        }

        if ($this->cardinal->toString() == Cardinal::WEST) {
            return new Position($this->x - 1, $this->y, $this->cardinal);
        }

        if ($this->cardinal->toString() == Cardinal::EAST) {
            return new Position($this->x + 1, $this->y, $this->cardinal);
        }
    }

    public function moveLeft():Position
    {
        return new Position($this->x, $this->y, $this->cardinal->moveLeft());
    }

    public function moveRight():Position
    {
        return new Position($this->x, $this->y, $this->cardinal->moveRight());
    }

    public function toString():string
    {
        return $this->x . " " . $this->y . " " . $this->cardinal->toString();
    }

}