<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Dto;
use SmirnovO\Mapper\Tests\Fixtures\DtoCastDefault;
use SmirnovO\Mapper\Tests\Fixtures\DtoErrors;
use SmirnovO\Mapper\Tests\Fixtures\DtoExample;
use SmirnovO\Mapper\Tests\Fixtures\DtoTestToArray;

/**
 * Class DtoTest
 */
#[CoversClass(Dto::class)]
class DtoTest extends TestCase
{
    /**
     * @return void
     */
    public function testNullAttribute(): void
    {
        $dto = new DtoExample();

        $this->assertFalse(isset($dto->hello));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testSetData(): void
    {
        $dto = new DtoExample(['test' => 'test']);

        $this->assertEquals('test', $dto->test);
    }

    /**
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
     * @return void
     * @throws Exception
     */
    public function testCast(): void
    {
        $dto = new DtoExample(['int1' => 100]);

        $this->assertEquals(200, $dto->cast);
    }

    /**
     * @return void
     */
    public function testParseOR(): void
    {
        $dto = new DtoExample(['int3' => 100, 'test' => 'test', 'a' => ['b' => 'foo']]);

        $this->assertEquals('foo', $dto->foo);
    }

    /**
     * @return void
     */
    public function testToArray(): void
    {
        $dto = new DtoTestToArray(['errors' => [], 'init' => [], 'str1' => 'str1', 'str2' => 'str2']);
        $dto->hello = 1;

        $this->assertIsArray($dto->toArray());
        $this->assertEquals([
            'str1' => 'str1',
            'str2' => 'str2',
        ], $dto->toArray());
    }

    /**
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
     * @return void
     * @throws Exception
     */
    public function testError(): void
    {
        $dto = new DtoErrors(['errors' => [], 'init' => []]);
        $dto->hello = 1;
        $this->assertNotEquals([], $dto->getErrors());
        $this->assertCount(2, $dto->getErrors());
    }

    /**
     * @return void
     */
    public function testCastMethod(): void
    {
        $dto = new DtoExample(['castMethod' => 'hello']);
        $this->assertEquals('hello', $dto->castMethod);

        $dto = new DtoExample([]);
        $this->assertEquals('vasa', $dto->castMethod);
    }

    public function testGetErrorsEmptyBeforeAnyBinding(): void
    {
        $dto = new DtoExample([]);
        $this->assertSame([], $dto->getErrors());
    }

    public function testGetErrorsAfterBindingError(): void
    {
        $dto = new DtoErrors(['errors' => [], 'init' => []]);
        $this->assertNotEmpty($dto->getErrors());
        $this->assertArrayHasKey('errors', $dto->getErrors());
        $this->assertArrayHasKey('init', $dto->getErrors());
    }

    public function testResolveByPropertyNameWhenNoElementName(): void
    {
        $dto = new DtoCastDefault(['castDefArray1' => [4, 5, 6]]);
        $this->assertEquals([4, 5, 6], $dto->castDefArray1);
    }
}
