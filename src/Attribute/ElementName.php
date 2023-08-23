<?php

namespace SmirnovO\Mapper\Attribute;

use Attribute;

/**
 * Class ElementName
 */
#[Attribute]
class ElementName
{
    /**
     * @param string $value
     */
    public function __construct(public string $value)
    {
    }
}
