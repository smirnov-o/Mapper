<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Attribute;

use Attribute;

/**
 * Class CastMethod
 */
#[Attribute]
class CastMethod
{
    /**
     * @param string $value
     */
    public function __construct(public string $value)
    {
    }
}
