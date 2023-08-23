<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * Class DtoExample01
 */
readonly class DtoExample extends Dto
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
     * @param int $val
     * @return int
     */
    public function cast(int $val): int
    {
        return $val + 100;
    }

    /**
     * @param int $val
     * @return string
     */
    public function cast1(int $val): string
    {
        return 'string';
    }
}
