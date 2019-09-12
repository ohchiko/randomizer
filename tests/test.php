<?php

// Autoload files using Composer autoload
require_once __DIR__.'/../vendor/autoload.php';

use Ohchiko\Randomizer\Randomizer;

// Default language
echo Randomizer::generate();

echo "\r\n";

// Custom language
echo Randomizer::generate(Randomizer::LANG_EN);

echo "\r\n";
