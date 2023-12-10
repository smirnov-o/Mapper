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
        $dto = new DtoExample(['test' => 'test']);

        $this->assertEquals('test', $dto->test);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testWrongType(): void
    {
        $dto = new DtoExample(['int' => 100]);

        $this->assertEquals('100', $dto->str);
        $this->assertFalse(isset($dto->array));
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testCast(): void
    {
        $dto = new DtoExample(['int' => 100]);

        $this->assertEquals(200, $dto->cast);
        $this->assertEquals('string', $dto->cast1);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testCastDefault(): void
    {
        $dto = new DtoExample(['int' => 100]);

        $this->assertEquals('string', $dto->castDefStr);
        $this->assertEquals(100, $dto->castDefInt);
        $this->assertEquals([1, 2, 3], $dto->castDefArray);
        $this->assertEquals([1, 2, 3], $dto->castDefArray1);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::toArray
     * @return void
     */
    public function testToArray(): void
    {
        $dto = new DtoExample(['int' => 100]);

        $this->assertIsArray($dto->toArray());
        $this->assertEquals([
            'int'           => 100,
            'str'           => '100',
            'cast'          => 200,
            'cast1'         => 'string',
            'castDefStr'    => 'string',
            'castDefInt'    => 100,
            'castDefArray'  => [1, 2, 3],
            'castDefArray1' => [1, 2, 3],
        ], $dto->toArray());
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::has
     * @return void
     */
    public function testHas(): void
    {
        $dto = new DtoExample(['int' => 100]);
        $this->assertTrue($dto->has('int'));
        $this->assertFalse($dto->has('dto'));
    }
}
