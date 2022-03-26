<?php

require_once("vendor/autoload.php");

use Wallbox\Models\Map as Map;
use Wallbox\Models\Cardinal as Cardinal;
use Wallbox\Models\Car as Car;
use Wallbox\Models\Position as Position;
use Wallbox\Models\FileReader as FileReader;
use Wallbox\Models\FileParser as FileParser;

try {
    $inputType = php_sapi_name();
    if ($inputType == 'cli') {
        $file = $argv[1] ?? 'test.txt';
        $outputType = 'cli';
    } else {
        $file = $_GET['file'] ?? 'test.txt';
        $outputType = 'html';
    }

    $fr = new FileReader($file);
    $data = $fr->read();

    $parser = new FileParser($data);
    $app = $parser->parse();
    $output=[];
    foreach ($app->map->getCars() as $key => $ev) {
        $ev->run($app->map);
        if ($outputType == 'cli') {
            print $ev->getPosition()->toString() . PHP_EOL;
        } else {
            print "<p>" . $ev->getPosition()->toString() . "</p>";
        }
    }
} catch (\Throwable $e) {
    print $e->getMessage();
}