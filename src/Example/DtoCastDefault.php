<?php
declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use PHPUnit\Framework\Attributes\CoversClass;
use SmirnovO\Mapper\Attribute\CastDefault;
use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 *  class DtoCastDefault
 */
final class DtoCastDefault extends Dto {
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
     * @var int|null
     */
    #[ElementName('null'), CastDefault(null)]
    public ?int $castDefNull;

    /**
     * @var bool
     */
    #[ElementName('false'), CastDefault(false)]
    public bool $castDefFalse;

    /**
     * @var bool
     */
    #[ElementName('true'), CastDefault(true)]
    public bool $castDefTrue;

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
}