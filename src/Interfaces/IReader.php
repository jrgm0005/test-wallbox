<?php

namespace Wallbox\Interfaces;

interface IReader
{
    const INVALID_INPUT = 'INVALID_INPUT';

    public function read(): object;
}