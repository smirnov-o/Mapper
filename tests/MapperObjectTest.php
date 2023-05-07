<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use PHPUnit\Framework\TestCase;

/**
 * Class MapperObjectTest
 */
class MapperObjectTest extends TestCase
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

        $class = new ObjectTest($array);
        $this->assertEquals('hello', $class->hi);
        $this->assertEquals(100, $class->foo);
        $this->assertEquals(['a' => 1], $class->bar);
        $this->assertEquals(1, $class->barr);
    }
}

class ObjectTest extends Mapper implements MapperObject
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
}



