<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Example\DtoErrors;
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
     * @throws Exception
     */
    public function testSetData(): void
    {
        $dto = new DtoExample(['test' => 'test']);

        $this->assertEquals('test', $dto->test);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     * @throws Exception
     */
    public function testSetBoolData(): void
    {
        $dto = new DtoExample(['bool' => true]);
        $this->assertTrue($dto->bool);

        $dto = new DtoExample(['bool' => false]);
        $this->assertFalse($dto->bool);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     * @throws Exception
     */
    public function testCastBoolData(): void
    {
        $dto = new DtoExample(['bool1' => true]);
        $this->assertFalse($dto->bool1);

        $dto = new DtoExample(['bool1' => false]);
        $this->assertTrue($dto->bool1);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     * @throws Exception
     */
    public function testWrongType(): void
    {
        $dto = new DtoExample(['int' => 100]);

        $this->assertEquals('100', $dto->int);
        $this->assertFalse(isset($dto->array));
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     * @throws Exception
     */
    public function testCast(): void
    {
        $dto = new DtoExample(['int1' => 100]);

        $this->assertEquals(200, $dto->cast);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     * @throws Exception
     */
    public function testCastDefault(): void
    {
        $dto = new DtoExample(['int2' => 100]);

        $this->assertEquals('string', $dto->castDefStr);
        $this->assertEquals(100, $dto->castDefInt);
        $this->assertEquals([1, 2, 3], $dto->castDefArray);
        $this->assertEquals([1, 2, 3], $dto->castDefArray1);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::parse
     * @return void
     */
    public function testParseOR(): void
    {
        $dto = new DtoExample(['int3' => 100, 'test' => 'test', 'a' => ['b' => 'foo']]);

        $this->assertEquals('foo', $dto->foo);
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::toArray
     * @return void
     */
    public function testToArray(): void
    {
        $dto = new DtoExample(['int4' => 100]);

        $this->assertIsArray($dto->toArray());
        $this->assertEquals([
            'cast1'         => 'string',
            'castDefStr'    => 'string',
            'castDefInt'    => 100,
            'castDefArray'  => [1, 2, 3],
            'castDefArray1' => [1, 2, 3],
            'castMethod'    => 'vasa',
        ], $dto->toArray());
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::has
     * @return void
     * @throws Exception
     */
    public function testHas(): void
    {
        $dto = new DtoExample(['int' => 100]);
        $this->assertTrue($dto->has('int'));
        $this->assertFalse($dto->has('dto'));
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoErrors::parse
     * @return void
     * @throws Exception
     */
    public function testError(): void
    {
        $dto = new DtoErrors(['errors' => 'Hello']);
        $this->assertEquals('Hello', $dto->errors);

        $dto = new DtoErrors(['errors' => 'Hello', 'init' => 100]);
        $this->assertTrue($dto->has('errors'));

        $dto = new DtoErrors(['errors' => [], 'errors1' => []]);
        $this->assertFalse($dto->has('errors'));
        $this->assertFalse($dto->has('errors1'));
        $this->assertNotEquals([], $dto->getErrors());
        $this->assertCount(2, $dto->getErrors());
        $this->assertEquals([], $dto->toArray());
    }

    /**
     * @covers \SmirnovO\Mapper\Example\DtoExample::castMethod
     * @return void
     */
    public function testCastMethod(): void
    {
        $dto = new DtoExample(['castMethod' => 'hello']);
        $this->assertEquals('vasa', $dto->castMethod);
    }
}
