<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Tests\Fixtures;

use SmirnovO\Mapper\Attribute\CastDefault;
use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

final class DtoCastDefault extends Dto
{
    #[ElementName('int4'), CastMethod('cast1')]
    public string $cast1;

    #[ElementName('hello'), CastDefault('string')]
    public string $castDefStr;

    #[ElementName('hello'), CastDefault(100)]
    public int $castDefInt;

    #[ElementName('false'), CastDefault(false)]
    public bool $castDefFalse;

    #[ElementName('true'), CastDefault(true)]
    public bool $castDefTrue;

    #[ElementName('hello'), CastDefault([1, 2, 3])]
    public array $castDefArray;

    #[CastDefault([1, 2, 3])]
    public array $castDefArray1;
}
