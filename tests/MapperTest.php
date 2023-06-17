<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Mapper;
use SmirnovO\Mapper\MapperObject;
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
        'a' => 1,
        'b' => [
            'a' => 1,
            'b' => [
                'a' => '3',
            ]
        ]
    ];

    /**
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
        $class->init($array);

        $this->assertEquals(1, $class->a);
        $this->assertEquals(0, $class->b);
        $this->assertEquals('100', $class->c);
        $this->assertEquals(100, $class->c);
    }

    /**
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
}
