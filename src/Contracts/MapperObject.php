<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Contracts;

/**
 * Interface MapperObject
 *
 * Marker for mappers that write mapped values to object properties instead of getData() array.
 */
interface MapperObject
{
    /**
     * Whether this mapper instance writes to object properties (true) or only to getData().
     *
     * @return bool
     */
    public function isMapperObject(): bool;
}
