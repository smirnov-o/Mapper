<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Attribute;

use Attribute;

/**
 * Class CastDefault
 */
#[Attribute]
class CastDefault
{
    /**
     * @param mixed $value
     */
    public function __construct(public mixed $value)
    {
    }
}
