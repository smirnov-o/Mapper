<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use PHPUnit\Framework\TestCase;

/**
 * Class MapperTestCast
 */
class MapperTestCast extends TestCase
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
                'a' => 1
            ]
        ];

        $class = new Test($array);
        $this->assertEquals('hello', $class->hi);
        $this->assertEquals(200, $class->foo);
        $this->assertEquals(['a' => 1], $class->bar);
        $this->assertInstanceOf(\stdClass::class, $class->barr);
    }
}

class Test extends Mapper implements MapperObject
{
    public ?string $hi;
    public int     $foo = 0;
    public         $bar;
    public mixed   $barr;

    public function getMap(): array
    {
        return [
            'hello' => 'hi',
            'foo'   => 'foo',
            'bar'   => 'bar',
            'bar.a' => 'barr'
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
        return 200;
    }

    public function Object(mixed $data)
    {
        return new \stdClass();
    }
}



