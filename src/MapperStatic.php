<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use SmirnovO\Mapper\Contracts\MapperObject;

/**
 * Class MapperStatic
 */
final class MapperStatic
{
    /**
     * @param array $source
     * @param array $map
     * @return Mapper
     */
    public static function getArray(array $source, array $map): Mapper
    {
        return new class ($source, $map) extends Mapper {
            public function getMap(): array
            {
                return [];
            }
        };
    }

    /**
     * @param array $source
     * @param array $map
     * @return Mapper
     */
    public static function getObject(array $source, array $map): Mapper
    {
        $class = new class ($source, $map) extends Mapper implements MapperObject {
            public function getMap(): array
            {
                return [];
            }
        };

        foreach ($class->getData() as $key => $item) {
            $class->{$key} = $item;
        }

        return $class;
    }
}
