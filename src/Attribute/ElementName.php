<?php

namespace SmirnovO\Mapper\Attribute;

use Attribute;

/**
 * Class ElementName
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ElementName
{
    /**
     * @param string $value
     */
    public function __construct(public string $value)
    {
    }
}
