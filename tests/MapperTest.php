<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Mapper;
use SmirnovO\Mapper\Contracts\MapperObject;
use SmirnovO\Mapper\MapperStatic;

/**
 * Class MapperTest
 */
class MapperTest extends TestCase
{
    /**
     * @var array
     */
    private array $array = [
        'a'   => 1,
        'b'   => [
            'a' => 1,
            'b' => [
                'a' => '3',
            ]
        ],
        'ddd' => 'Hello',
        'dd'  => [
            [
                'name' => 'Yes'
            ]
        ]
    ];

    /**
     * @covers \SmirnovO\Mapper\Mapper::getData
     * @return void
     */
    public function testClearData(): void
    {
        $class = $this->getMockBuilder(Mapper::class)->setConstructorArgs([[]])->getMock();
        $this->assertEquals([], $class->getData());

        $class = $this->getMockBuilder(Mapper::class)->setConstructorArgs([[1, 2, 3]])->getMock();
        $this->assertEquals([], $class->getData());

        $class = $this->getMockBuilder(Mapper::class)->setConstructorArgs([['1', '2', '3']])->getMock();
        $this->assertEquals([], $class->getData());
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::getData
     * @return void
     */
    public function testData(): void
    {
        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper {
            public function getMap(): array
            {
                return [
                    'aa'  => 'a',
                    'bb'  => 'b.b',
                    'ba'  => 'b.a',
                    'bba' => 'b.b.a',
                ];
            }
        };

        $this->assertEquals([
            'aa'  => 1,
            'bb'  => ['a' => 3],
            'ba'  => 1,
            'bba' => '3'
        ], $class->getData());
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::getMap
     * @return void
     */
    public function testProperty(): void
    {
        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper implements MapperObject {
            public ?int    $aa;
            public mixed   $bb;
            public         $ba;
            public ?string $bba;


            public function getMap(): array
            {
                return [
                    'aa'  => 'a',
                    'bb'  => 'b.b',
                    'ba'  => 'b.a',
                    'bba' => 'b.b.a',
                ];
            }
        };

        $this->assertEquals(1, $class->aa);
        $this->assertEquals(['a' => 3], $class->bb);
        $this->assertEquals(1, $class->ba);
        $this->assertEquals('3', $class->bba);
        $this->assertEquals([], $class->getData());
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::getCast
     * @return void
     */
    public function testCast(): void
    {
        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper implements MapperObject {
            public ?int    $aa;
            public mixed   $bb;
            public         $ba;
            public ?string $bba;


            public function getMap(): array
            {
                return [
                    'aa'  => 'a',
                    'bb'  => 'b.b',
                    'ba'  => 'b.a',
                    'bba' => 'b.b.a',
                ];
            }

            public function getCast(): array
            {
                return [
                    'aa'  => 'myCastOne',
                    'bba' => 'myCastTwo',
                ];
            }

            public function myCastOne(mixed $data)
            {
                return $data + 100;
            }

            public function myCastTwo(mixed $data): string
            {
                return $data . 'Hello';
            }
        };

        $this->assertEquals(101, $class->aa);
        $this->assertEquals(['a' => 3], $class->bb);
        $this->assertEquals(1, $class->ba);
        $this->assertEquals('3Hello', $class->bba);
        $this->assertEquals([], $class->getData());
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::getData
     * @return void
     */
    public function testType(): void
    {
        $array = [
            'a' => '1',
            'b' => 'Hello',
            'c' => 100
        ];

        $class = new class ($array) extends SmirnovO\Mapper\Mapper implements MapperObject {
            public int   $a;
            public int   $b;
            public int   $c;
            public array $e;

            public function getMap(): array
            {
                return [
                    'a' => 'a',
                    'b' => 'b',
                    'c' => 'c',
                    'e' => 'b'
                ];
            }
        };

        $this->assertEquals(1, $class->a);
        $this->assertEquals(0, $class->b);
        $this->assertEquals('100', $class->c);
        $this->assertEquals(100, $class->c);
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::init
     * @return void
     */
    public function testInit(): void
    {
        $array = [
            'a' => '1',
            'b' => 'Hello',
            'c' => 100
        ];

        $class = new class () extends SmirnovO\Mapper\Mapper implements MapperObject {
            public int $a;
            public int $b;
            public int $c;

            public function getMap(): array
            {
                return [
                    'a' => 'a',
                    'b' => 'b',
                    'c' => 'c',
                ];
            }
        };
        $class->init($array);

        $this->assertEquals(1, $class->a);
        $this->assertEquals(0, $class->b);
        $this->assertEquals('100', $class->c);
        $this->assertEquals(100, $class->c);
    }

    /**
     * @covers \SmirnovO\Mapper\MapperStatic::getArray
     * @covers \SmirnovO\Mapper\MapperStatic::getObject
     * @return void
     */
    public function testStatic(): void
    {
        $array = ['a' => '1', 'b' => 100];
        $maps = ['a' => 'a', 'b' => 'b'];
        $data = MapperStatic::getArray($array, $maps)->getData();

        $this->assertEquals(1, $data['a']);
        $this->assertEquals(100, $data['b']);

        $data = MapperStatic::getObject($array, $maps);

        $this->assertEquals(1, $data->a);
        $this->assertEquals(100, $data->b);
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::parse
     * @return void
     */
    public function testSomeFiled(): void
    {
        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper implements MapperObject {
            public int    $f;
            public int    $g;
            public string $c;
            public string $h;
            public string $y;

            public function getMap(): array
            {
                return [
                    'f' => 'a',
                    'c' => 'b.b.a||b.a',
                    'g' => 'a.c.c||b.a',
                    'h' => 'ddd||dd.0.name',
                    'y' => 'ddd.0.name||dd.0.name'
                ];
            }
        };

        $this->assertEquals(1, $class->f);
        $this->assertEquals('3', $class->c);
        $this->assertEquals(1, $class->g);
        $this->assertEquals('Hello', $class->h);
        $this->assertEquals('Yes', $class->y);
    }

    /**
     * @covers \SmirnovO\Mapper\Mapper::isNotEmpty
     * @return void
     */
    public function testisEmpty(): void
    {
        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper implements MapperObject {
            public int    $f;
            public int    $g;
            public array  $c = [];
            public bool   $h = false;
            public string $y;

            public function getMap(): array
            {
                return [
                    'f' => 'a.dd',
                    'c' => 'b.b.a.d||b.a.d',
                    'g' => 'a.c.c.2||b.a.f',
                    'h' => 'ddd.ff||dd.0.name.f',
                    'y' => 'ddd.0.name.gg||dd.0.name.fg'
                ];
            }
        };

        $this->assertFalse($class->isNotEmpty());

        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper implements MapperObject {
            public int    $f;

            public function getMap(): array
            {
                return [
                    'f' => 'a',
                ];
            }
        };

        $this->assertTrue($class->isNotEmpty());

        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper {
            public function getMap(): array
            {
                return [
                    'aa'  => 'a',
                ];
            }
        };

        $this->assertEquals(['aa'  => 1], $class->getData());
        $this->assertTrue($class->isNotEmpty());


        $class = new class ($this->array) extends SmirnovO\Mapper\Mapper {
            public function getMap(): array
            {
                return [
                    'aa'  => 'a.2.2',
                ];
            }
        };

        $this->assertEquals([], $class->getData());
        $this->assertFalse($class->isNotEmpty());
    }
}
