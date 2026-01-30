<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Tests\Fixtures\DtoCanBeNull;

/**
 *  class DtoCanBeNullTest
 */
#[CoversClass(\SmirnovO\Mapper\Tests\Fixtures\DtoCanBeNull::class)]
class DtoCanBeNullTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     */
    public function test(): void
    {
        $dto = new DtoCanBeNull(['a' => 1, 'b' => null,'d' => null]);

        $this->assertEmpty($dto->b);
        $this->assertNotTrue(isset($dto->d));
        $this->assertEquals(1, $dto->a);
        $this->assertEquals(null, $dto->b);
        $this->assertEquals(['a' => 1, 'b' => null], $dto->toArray());


        $dto = new DtoCanBeNull(['a' => null, 'b' => 1]);

        $this->assertNotTrue(isset($dto->a));
        $this->assertEquals(1, $dto->b);

        $dto = new DtoCanBeNull([]);
        self::assertEquals([],$dto->toArray());
    }
}