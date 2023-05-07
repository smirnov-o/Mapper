<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use PHPUnit\Framework\TestCase;

/**
 * Class MapperCastTest
 */
class MapperCastTest extends TestCase
{
    /**
     * @return void
     */
    public function testClearData(): void
    {
        $array = [
            'hello' => 'hello',
            'foo'   => 100,
            'bar'   => [
                'a' => 1,
            ],
            'arr'   => 1
        ];

        $class = new TestCast($array);
        $this->assertEquals('hello', $class->hi);
        $this->assertEquals(300, $class->foo);
        $this->assertEquals(['a' => 1], $class->bar);
        $this->assertInstanceOf(\stdClass::class, $class->barr);
    }
}

class TestCast extends Mapper implements MapperObject
{
    public ?string $hi;
    public int     $foo = 0;
    public         $bar;
    public mixed   $barr;
    public ?array  $array;

    public function getMap(): array
    {
        return [
            'hello' => 'hi',
            'foo'   => 'foo',
            'bar'   => 'bar',
            'bar.a' => 'barr',
            'arr' => 'array'
        ];
    }

    public function getCast(): array
    {
        return [
            'foo'  => 'Sum',
            'barr' => 'Object'
        ];
    }

    public function sum(mixed $data)
    {
        return $data + 200;
    }

    public function Object(mixed $data)
    {
        return new \stdClass();
    }
}



