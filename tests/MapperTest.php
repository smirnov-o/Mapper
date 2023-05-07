<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Mapper;
use SmirnovO\Mapper\MapperObject;

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
                    'a'     => 'aa',
                    'b.b'   => 'bb',
                    'b.a'   => 'ba',
                    'b.b.a' => 'bba'
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
            public $ba;
            public ?string $bba;


            public function getMap(): array
            {
                return [
                    'a'     => 'aa',
                    'b.b'   => 'bb',
                    'b.a'   => 'ba',
                    'b.b.a' => 'bba'
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
            public $ba;
            public ?string $bba;


            public function getMap(): array
            {
                return [
                    'a'     => 'aa',
                    'b.b'   => 'bb',
                    'b.a'   => 'ba',
                    'b.b.a' => 'bba'
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
}
