<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Example\DtoExample;

/**
 * Class DtoTest
 */
class DtoTest extends TestCase
{
    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testNullAttribute(): void
    {
        $dto = new DtoExample();

        $this->assertFalse(isset($dto->hello));
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testSetData(): void
    {
        $dto = new DtoExample([
            'test' => 'test'
        ]);

        $this->assertEquals('test', $dto->test);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testWrongType(): void
    {
        $dto = new DtoExample([
            'int' => 100
        ]);

        $this->assertEquals('100', $dto->str);
        $this->assertNull($dto->array);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testCast(): void
    {
        $dto = new DtoExample([
            'int' => 100
        ]);

        $this->assertEquals(200, $dto->cast);
        $this->assertEquals('string', $dto->cast1);
    }
}
