<?php

// Autoload files using Composer autoload
require_once __DIR__.'/../vendor/autoload.php';

use Ohchiko\Randomizer\Randomizer;

echo Randomizer::generate();
