<?php

namespace Wallbox\Tests;

use PHPUnit\Framework\TestCase;

use Wallbox\Models\Map as Map;
use Wallbox\Models\Cardinal as Cardinal;
use Wallbox\Models\Car as Car;
use Wallbox\Models\Position as Position;

class TestCases extends TestCase
{

    // A few unit tests.

    public function testInvalidMapLimitX()
    {
        $limitX = -1;
        $limitY = 2;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Map::INVALID_LIMIT_X);
        $map = new Map($limitX, $limitY);
    }

    public function testInvalidMapLimitY()
    {
        $limitX = 2;
        $limitY = -1;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Map::INVALID_LIMIT_Y);
        $map = new Map($limitX, $limitY);
    }

    public function testInvalidCarPosition()
    {
        $limitX = 2;
        $limitY = 2;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Map::INVALID_POSITION);
        $map = new Map($limitX, $limitY);

        $carPosition = new Position(1,3, new Cardinal(Cardinal::NORTH));
        $movements = 'MMM';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);
        $map->addCar($car);
    }

    public function testCarCrash()
    {
        $limitX = 2;
        $limitY = 2;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Map::CARS_CRASH);
        $map = new Map($limitX, $limitY);

        $carPosition = new Position(1,2, new Cardinal(Cardinal::NORTH));
        $movements = 'MMM';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);
        $map->addCar($car);
    }

    public function testCardWithInvalidMovements()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Car::INVALID_MOVEMENTS);
        $carPosition = new Position(1,2, new Cardinal(Cardinal::NORTH));
        $movements = 'XYZ';
        $car = new Car($carPosition, $movements);
    }

    public function testInvalidCarding()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Cardinal::INVALID_CARDINAL);
        $cardinal = new Cardinal('Z');
    }

    public function testMoveRightFromNorthShouldEndHeadingEast()
    {
        $cardinal = new Cardinal(Cardinal::NORTH);
        $expectedHeading = new Cardinal(Cardinal::EAST);
        $heading = $cardinal->moveRight();
        $this->assertEquals($expectedHeading->toString() ,$heading->toString());
    }

    public function testMoveLeftFromNorthShouldEndHeadingWest()
    {
        $cardinal = new Cardinal(Cardinal::NORTH);
        $expectedHeading = new Cardinal(Cardinal::WEST);
        $heading = $cardinal->moveLeft();
        $this->assertEquals($expectedHeading->toString() ,$heading->toString());
    }

    public function testPositionHeadingNorthAndMoveToRightShouldHeadingToEast()
    {
        $position = new Position(1,3, new Cardinal(Cardinal::NORTH));
        $expectedPosition = new Position(1,3, new Cardinal(Cardinal::EAST));
        $newPosition = $position->moveRight();
        $this->assertEquals($expectedPosition, $newPosition);
    }

    public function testPositionHeadingNorthAndMoveToRightShouldHeadingToWest()
    {
        $position = new Position(1,3, new Cardinal(Cardinal::NORTH));
        $expectedPosition = new Position(1,3, new Cardinal(Cardinal::WEST));
        $newPosition = $position->moveLeft();
        $this->assertEquals($expectedPosition, $newPosition);
    }

    // A few function tests.

    public function testCarMovingForward()
    {
        $limitX = 5;
        $limitY = 5;
        $map = new Map($limitX, $limitY);

        $carPosition = new Position(1,2, new Cardinal(Cardinal::NORTH));
        $movements = 'MMM';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);

        $expectedFinalPosition = new Position(1,5, new Cardinal(Cardinal::NORTH));

        $map->getCars()[0]->run($map);
        $this->assertEquals($expectedFinalPosition->toString(), $map->getCars()[0]->getPosition()->toString());
    }

    public function testCarMovingForwardTurnAndMovingForwardAgain()
    {

        $limitX = 5;
        $limitY = 5;
        $map = new Map($limitX, $limitY);

        $carPosition = new Position(1,2, new Cardinal(Cardinal::NORTH));
        $movements = 'MLMRMR';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);

        $expectedFinalPosition = new Position(0,4, new Cardinal(Cardinal::EAST));

        $map->getCars()[0]->run($map);
        $this->assertEquals($expectedFinalPosition->toString(), $map->getCars()[0]->getPosition()->toString());
    }

    public function testCarMovingOutOfMap()
    {

        $limitX = 3;
        $limitY = 3;
        $map = new Map($limitX, $limitY);

        $carPosition = new Position(1,2, new Cardinal(Cardinal::NORTH));
        $movements = 'MMM';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Map::INVALID_POSITION);
        $expectedFinalPosition = new Position(0,4, new Cardinal(Cardinal::EAST));

        $map->getCars()[0]->run($map);
    }

    public function testCarsCrashingInMovement()
    {

        $limitX = 4;
        $limitY = 4;
        $map = new Map($limitX, $limitY);

        $carPosition = new Position(1,2, new Cardinal(Cardinal::NORTH));
        $movements = 'MM';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);


        $carPosition = new Position(1,1, new Cardinal(Cardinal::NORTH));
        $movements = 'MMM';
        $car = new Car($carPosition, $movements);
        $map->addCar($car);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Map::CARS_CRASH);

        $map->getCars()[0]->run($map);
        $map->getCars()[1]->run($map);
    }

}