<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Internal;

use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * Writes value to object property with optional type casting.
 */
final class PropertyWriter
{
    /**
     * @var array<string, ReflectionClass<object>>
     */
    private static array $classCache = [];

    /**
     * @var array<string, array<string, ReflectionProperty|null>>
     */
    private static array $propertyCache = [];

    /**
     * @var list<string>
     */
    private const TYPE_NAMES = ['boolean', 'bool', 'integer', 'int', 'float', 'double', 'string', 'array', 'object', 'null'];

    /**
     * @param object $target
     * @param string $key Property name
     * @param mixed $value
     * @param bool $strict When true, incompatible types are not cast (write fails)
     * @return bool
     */
    public static function write(object $target, string $key, mixed $value, bool $strict = false): bool
    {
        $className = $target::class;
        $prop = self::getProperty($className, $key);

        if ($prop === null) {
            return false;
        }

        $type = $prop->getType();
        if (!$type instanceof ReflectionNamedType) {
            $target->{$key} = $value;
            return true;
        }

        $typeName = $type->getName();

        if (!in_array($typeName, self::TYPE_NAMES, true)) {
            $target->{$key} = $value;
            return true;
        }

        if ($strict && !self::isCompatibleType($value, $typeName)) {
            return false;
        }

        $cast = $value;
        $result = settype($cast, $typeName);

        if ($result) {
            $target->{$key} = $cast;
        }

        return $result;
    }

    /**
     * @param string $className
     * @param string $key
     * @return ReflectionProperty|null
     */
    private static function getProperty(string $className, string $key): ?ReflectionProperty
    {
        if (isset(self::$propertyCache[$className][$key])) {
            return self::$propertyCache[$className][$key] ?: null;
        }

        if (!isset(self::$classCache[$className])) {
            try {
                self::$classCache[$className] = new ReflectionClass($className);
            } catch (ReflectionException) {
                self::$propertyCache[$className] = [$key => null];
                return null;
            }
        }

        $ref = self::$classCache[$className];

        try {
            $prop = $ref->getProperty($key);
        } catch (ReflectionException) {
            $prop = null;
        }

        if (!isset(self::$propertyCache[$className])) {
            self::$propertyCache[$className] = [];
        }
        self::$propertyCache[$className][$key] = $prop;

        return $prop;
    }

    private static function isCompatibleType(mixed $value, string $typeName): bool
    {
        return match ($typeName) {
            'bool', 'boolean' => is_bool($value) || $value === null,
            'int', 'integer' => is_int($value) || (is_string($value) && is_numeric($value)) || $value === null,
            'float', 'double' => is_float($value) || is_int($value) || (is_string($value) && is_numeric($value)) || $value === null,
            'string' => is_string($value) || is_numeric($value) || $value === null,
            'array' => is_array($value) || $value === null,
            'object' => is_object($value) || $value === null,
            'null' => $value === null,
            default => true,
        };
    }
}
