<?php

namespace Wallbox\Models;

use Wallbox\Interfaces\IReader;
use stdClass;

class FileReader implements IReader
{
    const INVALID_PATH = 'INVALID_PATH';
    const ERROR_FILE_DOES_NOT_EXIST = 'ERROR_FILE_DOES_NOT_EXIST';
    protected $path = '';

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function read():object
    {
        $response = new stdClass;
        if (empty($this->path)) {
            throw new \Exception(self::INVALID_PATH);
        }

        if(!file_exists($this->path)) {
            throw new \Exception(self::ERROR_FILE_DOES_NOT_EXIST);
        }

        $response->data = [];
        if (($fileHandler = fopen($this->path, "r")) !== FALSE) {
            while (($data = fgetcsv($fileHandler, 1000, ",")) !== FALSE) {
                $response->data[] = $data;
            }
            fclose($fileHandler);
            return $response;
        } else {
            throw new \Exception(self::ERROR_UNABLE_TO_READ_FILE);
        }
    }
}