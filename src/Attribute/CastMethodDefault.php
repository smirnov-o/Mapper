<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Attribute;

use Attribute;

/**
 * class CastMethodDefault
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class CastMethodDefault
{
    /**
     * @param string $value
     */
    public function __construct(public string $value)
    {
    }
}
