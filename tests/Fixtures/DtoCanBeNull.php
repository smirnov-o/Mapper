<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Tests\Fixtures;

use SmirnovO\Mapper\Attribute\CanBeNull;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

final class DtoCanBeNull extends Dto
{
    #[ElementName('a')]
    public float $a;

    #[ElementName('b'), CanBeNull()]
    public ?float $b;

    #[ElementName('c')]
    public ?float $c;

    #[ElementName('d')]
    public float $d;
}
