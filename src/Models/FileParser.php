<?php

namespace Wallbox\Models;

use Wallbox\Interfaces\IParser;
use Wallbox\Models\Cardinal;
use Wallbox\Models\Position;
use Wallbox\Models\Car;
use Wallbox\Models\Map;

use stdClass;

class FileParser implements IParser
{
    const INVALID_MAP_LIMITS = 'INVALID_MAP_LIMITS';
    const INVALID_EVS_INPUT = 'INVALID_EVS_INPUT';
    const INVALID_INPUT = 'INVALID_INPUT';
    const INVALID_CAR_POSITION_INPUT = 'INVALID_CAR_POSITION_INPUT';
    const MAP_PARTS = 2;
    const CAR_POSITION_PARTS = 3;

    protected $input;

    public function __construct(object $input)
    {
        if(!isset($input->data) || !is_array($input->data))
            throw new Exception(self::INVALID_INPUT);
        $this->input = $input;
    }

    public function parse():object
    {
        return $this->extractInfo($this->input->data);
    }

    protected function getMapLimits(string $data):object
    {
        if (empty($data)) throw new \Exception(self::EMPTY_MAP_LIMITS);

        $parts = explode(' ', $data);
        if(count($parts) != self::MAP_PARTS) throw new \Exception(self::INVALID_MAP_LIMITS);
        if(!$this->isInteger($parts[0])) throw new \Exception(self::INVALID_MAP_LIMITS);
        if(!$this->isInteger($parts[1])) throw new \Exception(self::INVALID_MAP_LIMITS);

        $response = new stdClass;
        $response->limitX = (int)$parts[0];
        $response->limitY = (int)$parts[1];
        return $response;
    }

    protected function extractInfo(array $data):object
    {
        $app = new stdClass;

        // Get map limits.
        $limits = $this->getMapLimits($data[0][0]);
        $app->map = new Map($limits->limitX, $limits->limitY);
        // Skip map limits.
        unset($data[0]);

        // Validate cars input.
        $limit = count($data); // Not needed to calculate anything more. Due to we already removed the first one and we need to have a pair input for cars.
        if ($limit < 2) throw new \Exception(self::INVALID_EVS_INPUT);
        if ($limit % 2 != 0) throw new \Exception(self::INVALID_EVS_INPUT);

        $cars = [];
        for ($i=1; $i < $limit ; $i= $i+2) {
            $carPosition = $this->getCarPosition($data[$i][0]);
            $carMovements = $data[$i+1][0];
            $car = new Car($carPosition, $carMovements);
            $app->map->addCar($car);
        }

        return $app;

    }

    protected function getCarPosition(string $data):Position
    {
        $input = explode(" ", $data);

        if(count($input) != self::CAR_POSITION_PARTS) throw new \Exception(self::INVALID_CAR_POSITION_INPUT);
        if(!$this->isInteger($input[0])) throw new \Exception(self::INVALID_CAR_POSITION_INPUT);
        if(!$this->isInteger($input[1])) throw new \Exception(self::INVALID_CAR_POSITION_INPUT);
        $cardinal = new Cardinal($input[2]);
        $position = new Position($input[0], $input[1], $cardinal);
        return $position;
    }

    protected function isInteger($input):bool
    {
        return(ctype_digit(strval($input)));
    }
}