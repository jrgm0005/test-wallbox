<?php

namespace Wallbox\Models;

use Wallbox\Models\Position as Position;

class Map
{
    const INVALID_LIMIT_X = 'INVALID_LIMIT_X';
    const INVALID_LIMIT_Y = 'INVALID_LIMIT_Y';
    const INVALID_POSITION = 'INVALID_POSITION';
    const CARS_CRASH = 'CARS_CRASH';

    protected $limitX;
    protected $limitY;
    protected $cars;

    public function __construct(int $limitX, int $limitY)
    {
        if ($limitX <= 0) throw new \Exception(self::INVALID_LIMIT_X);
        if ($limitY <= 0) throw new \Exception(self::INVALID_LIMIT_Y);
        $this->limitY = $limitY;
        $this->limitX = $limitX;
        $this->cars = [];
    }

    public function isValidPosition(Position $position, int $id = -1):bool
    {
        $validX = $position->getX() <= $this->limitX && $position->getX() >= 0;
        $validY = $position->getY() <= $this->limitY && $position->getY() >= 0;

        if (!$validX || !$validY) throw new \Exception(self::INVALID_POSITION);


        foreach ($this->cars as $key => $car) {

            // Skip current car position.
            if ($id >= 0 && $id == $car->getID()) {
                continue;
            }
            if ($car->getPosition()->getX() == $position->getX() && $car->getPosition()->getY() == $position->getY()) {
                throw new \Exception(self::CARS_CRASH);
            }
        }

        return true;
    }

    public function addCar(Car $car)
    {
        if ($this->isValidPosition($car->getPosition())) {
            $car->setID(count($this->cars));
            $this->cars[] = $car;
        }
    }

    public function getCars():array
    {
        return $this->cars;
    }
}