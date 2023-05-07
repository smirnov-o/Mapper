<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class MapperTest
 */
class MapperTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function testGetData(): void
    {
        $class = $this->createMock(MapperContract::class);
        $this->assertEquals([], $class->getData());
        $this->assertEquals([], $class->getMap());
        $this->assertEquals([], $class->getCast());
    }
}
