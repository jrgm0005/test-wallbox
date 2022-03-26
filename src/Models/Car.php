<?php

namespace Wallbox\Models;

use Wallbox\Models\Position as Position;
use Wallbox\Models\Map as Map;

use \Exception as Exception;
class Car
{
    const INVALID_MOVEMENTS = 'INVALID_MOVEMENTS';
    const MOVE_FORWARD = 'M';
    const MOVE_LEFT = 'L';
    const MOVE_RIGHT = 'R';

    const MOVEMENTS = [
        self::MOVE_FORWARD => 'forward',
        self::MOVE_RIGHT => 'moveRight',
        self::MOVE_LEFT => 'moveLeft'
    ];

    protected $id;
    protected $position;
    protected $movements;

    public function __construct(Position $position, string $movements)
    {
        $this->position = $position;
        $this->validateMovements($movements);
        $this->movements = $movements;
    }

    public function validateMovements(string $movements):bool
    {
        $movementArray = str_split($movements);
        foreach ($movementArray as $key => $movement) {
            if (!in_array($movement, array_keys(self::MOVEMENTS))) {
                throw new Exception(self::INVALID_MOVEMENTS);
            }
        }
        return true;
    }

    public function run(Map $map)
    {
        $movementArray = str_split($this->movements);
        foreach ($movementArray as $key => $movement) {
            $movementFunction = self::MOVEMENTS[$movement];
            $newPosition = $this->position->$movementFunction();
            if ($map->isValidPosition($newPosition, $this->id)) {
                $this->position = $newPosition;
            }
        }
    }

    public function getID():int
    {
        return $this->id;
    }

    public function setID(int $id)
    {
        $this->id = $id;
    }

    public function getPosition():Position
    {
        return $this->position;
    }

    public function setPosition(Position $p)
    {
        $this->position = $p;
    }

    public function getMovements():string
    {
        return $this->movements;
    }
}