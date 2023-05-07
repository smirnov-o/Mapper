<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use PHPUnit\Framework\TestCase;

/**
 * Class MapperTestObject
 */
class MapperTestObject extends TestCase
{
    /**
     * @return void
     */
    public function testClearData(): void
    {
        $class = new Test([]);
        $d = 1;
    }
}

class Test extends Mapper implements MapperObject
{
    public function getMap(): array
    {
        return [''];
    }
}



