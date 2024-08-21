<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use SmirnovO\Mapper\Attribute\CastDefault;
use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\CastMethodDefault;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * Class DtoExample01
 */
final class DtoExample extends Dto
{
    /**
     * @var string
     */
    public string $hello;

    /**
     * @var string
     */
    #[ElementName('test')]
    public string $test;

    /**
     * @var int
     */
    #[ElementName('int')]
    public int $int;

    /**
     * @var int
     */
    #[ElementName('int1'), CastMethod('cast')]
    public int $cast;

    /**
     * @var array<string, mixed>|null
     */
    #[ElementName('int2')]
    public ?array $array;

    /**
     * @var string
     */
    #[ElementName('int4'), CastMethod('cast1')]
    public string $cast1;

    /**
     * @var string
     */
    #[ElementName('hello'), CastDefault('string')]
    public string $castDefStr;

    /**
     * @var int
     */
    #[ElementName('hello'), CastDefault(100)]
    public int $castDefInt;

    /**
     * @var array<int>
     */
    #[ElementName('hello'), CastDefault([1, 2, 3])]
    public array $castDefArray;

    /**
     * @var array<int>
     */
    #[CastDefault([1, 2, 3])]
    public array $castDefArray1;

    /**
     * @var string
     */
    #[ElementName('bar||a.b')]
    public string $foo;

    /**
     * @var bool
     */
    #[ElementName('bool')]
    public bool $bool;

    /**
     * @var bool
     */
    #[ElementName('bool1'), CastMethod('castBool')]
    public bool $bool1;

    /**
     * @var string
     */
    #[ElementName('castMethod'), CastMethodDefault('castMethod')]
    public string $castMethod;

    /**
     * @param bool $val
     *
     * @return bool
     */
    public function castBool(bool $val): bool
    {
        return !$val;
    }

    /**
     * @param int $val
     *
     * @return int
     */
    public function cast(int $val): int
    {
        return $val + 100;
    }

    /**
     * @return string
     */
    public function cast1(): string
    {
        return 'string';
    }

    /**
     * @return string
     */
    public function castMethod(): string
    {
        return 'vasa';
    }
}
