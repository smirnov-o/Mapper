<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;


use PHPUnit\Framework\TestCase;

/**
 * Class MapperTest
 */
class MapperTest extends TestCase
{
    /**
     * @return void
     */
    public function testIndex(): void
    {
        $class = new Mapper();

        $this->assertTrue($class->index());
    }
}
