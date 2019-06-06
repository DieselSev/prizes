<?php

namespace tests\unit\tools;

use Faker\Factory;
use Faker\Generator;

/**
 * Class ModelFactory
 * @package tests\unit\tools
 */
class ModelFactory
{
    /**
     * @return Generator
     */
    protected static function getFaker(): Generator
    {
        return Factory::create();
    }
}
