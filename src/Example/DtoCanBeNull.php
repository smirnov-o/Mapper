<?php
declare(strict_types=1);

namespace SmirnovO\Mapper\Example;

use SmirnovO\Mapper\Attribute\CanBeNull;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Dto;

/**
 * class DtoCanBeNull
 */
final class DtoCanBeNull extends Dto {
    /**
     * @var float
     */
    #[ElementName('a')]
    public float $a;

    /**
     * @var float|null
     */
    #[ElementName('b'), CanBeNull()]
    public ?float $b;

    /**
     * @var float|null
     */
    #[ElementName('c')]
    public ?float $c = null;

    /**
     * @var float
     */
    #[ElementName('d'), CanBeNull()]
    public float $d;
}