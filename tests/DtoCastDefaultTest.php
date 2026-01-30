<?php
declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SmirnovO\Mapper\Tests\Fixtures\DtoCastDefault;

/**
 *  class DtoCastDefaultTest
 */
#[CoversClass(\SmirnovO\Mapper\Tests\Fixtures\DtoCastDefault::class)]
class DtoCastDefaultTest extends TestCase {
    /**
     * @return void
     * @throws Exception
     */
    public function testCastDefault(): void
    {
        $dto = new DtoCastDefault(['int2' => null]);

        $this->assertEquals('string', $dto->castDefStr);
        $this->assertEquals(100, $dto->castDefInt);
        $this->assertEquals([1, 2, 3], $dto->castDefArray);
        $this->assertEquals([1, 2, 3], $dto->castDefArray1);
        $this->assertFalse($dto->castDefFalse);
        $this->assertTrue($dto->castDefTrue);
    }
}