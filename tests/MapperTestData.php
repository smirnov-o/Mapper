<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use PHPUnit\Framework\TestCase;

/**
 * Class MapperTestData
 */
class MapperTestData extends TestCase
{
    /**
     * @return void
     */
    public function testClearData(): void
    {
        $data = new Test([]);
        $this->assertEquals([], $data->getData());

        $data = new Test([1, 2, 4]);
        $this->assertEquals([], $data->getData());

        $data = new Test(['3', '2']);
        $this->assertEquals([], $data->getData());

        $data = new Test1(['3', '2']);
        $this->assertEquals([], $data->getData());

        $data = new Test2(['3', '2']);
        $this->assertEquals([], $data->getData());
    }

    /**
     * @return void
     */
    public function testData(): void
    {
        $array = [
            'a' => 1,
            'b' => 2,
            'c' => [
                'a' => 1,
                'b' => 2,
                'c' => [
                    'a' => 1,
                    'b' => 2
                ]
            ]
        ];

        $data = new Test3($array);
        $this->assertEquals([
            'aa'  => 1,
            'bb'  => 2,
            'cc'  => [
                'a' => 1,
                'b' => 2
            ],
            'ca'  => 1,
            'ccb' => 2
        ], $data->getData());
    }
}

/**
 *
 */
class Test3 extends Mapper
{
    public function getMap(): array
    {
        return [
            'a'     => 'aa',
            'b'     => 'bb',
            'c.c'   => 'cc',
            'c.a'   => 'ca',
            'c.c.b' => 'ccb'
        ];
    }
}

/**
 *
 */
class Test2 extends Mapper
{
    public function getMap(): array
    {
        return ['1', '2', '3'];
    }
}

/**
 *
 */
class Test1 extends Mapper
{
    public function getMap(): array
    {
        return [1, 2, 3];
    }
}

/**
 *
 */
class Test extends Mapper
{
    public function getMap(): array
    {
        return [''];
    }
}



