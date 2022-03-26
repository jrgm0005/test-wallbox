# test-wallbox

A simple app to calculate the movement of some auto EV in a map.

A group of electric vehicles (EV) with autopilot are being landed by Wallbox on a city.

Created using a file as input, code is prepared to be replaced for another input way.

Output can be in two different ways.

* bash output
* browser output

Include unit tests and few function tests.

# How to use

1. Download the repo
2. Run composer install
3. Now you can run this in 2 ways. Via browser or via bash.
    
    3.1. To do it via bash you just need to do `php index.php "test.txt"`

    NOTE: you can use another input file or none that will use test.txt by default

    3.2. To do it via browser just need to go to serverURL/index.php and will use the test.file by default

    NOTE: you can pass a get param called file to use another test file
    
4. To run unit tests you just need to do next command

    ```
     vendor/phpunit/phpunit/phpunit tests/TestCases.php
    ```
    
5. If any queries just let me know and I will be pleased to help
