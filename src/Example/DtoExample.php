<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use SmirnovO\Mapper\Attribute\CastDefault;
use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * Class DtoExample01
 */
class DtoExample extends Dto
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
     * @var string|null
     */
    #[ElementName('int')]
    public ?string $str;

    /**
     * @var array|null
     */
    #[ElementName('int')]
    public ?array $array;

    /**
     * @var int
     */
    #[ElementName('int'), CastMethod('cast')]
    public int $cast;

    /**
     * @var string
     */
    #[ElementName('int'), CastMethod('cast1')]
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
     * @var array
     */
    #[ElementName('hello'), CastDefault([1,2,3])]
    public array $castDefArray;

    /**
     * @var array
     */
    #[CastDefault([1,2,3])]
    public array $castDefArray1;

    /**
     * @param int $val
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
}
