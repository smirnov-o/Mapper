<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use ReflectionClass;
use ReflectionProperty;
use SmirnovO\Mapper\Attribute\CanBeNull;
use SmirnovO\Mapper\Attribute\CastDefault;
use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\CastMethodDefault;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Contracts\DtoContract;
use Throwable;

use function array_key_exists;
use function array_reduce;
use function explode;
use function is_string;
use function method_exists;

/**
 * Class Dto
 *
 * Attribute processing order (fixed): 1) resolve value (ElementName or property name),
 * 2) CastDefault, 3) CastMethodDefault, 4) CastMethod, 5) CanBeNull.
 */
abstract class Dto implements DtoContract
{
    /**
     * @var array<string, string>
     */
    private array $errors = [];

    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $reflectionCache = [];

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->parse($data);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return $this
     */
    public function init(array $data): static
    {
        $this->parse($data);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $cache = self::getReflectionCache(static::class);
        $array = [];

        foreach ($cache['publicProperties'] as $name => $property) {
            try {
                $array[$name] = $property->getValue($this);
            } catch (Throwable) {
                // Skip uninitialized or inaccessible properties
                continue;
            }
        }

        return $array;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->{$key});
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    private function parse(array $data): void
    {
        $cache = self::getReflectionCache(static::class);

        foreach ($cache['properties'] as $meta) {
            $prop = $meta['property'];
            $value = $this->resolveValue($meta['elementKey'], $prop->getName(), $data);

            $value = $this->applyCastDefault($value, $meta['castDefault']);
            $value = $this->applyCastMethodDefault($value, $meta['castMethodDefault']);
            $value = $this->applyCastMethod($value, $meta['castMethod']);
            $value = $this->applyCanBeNull($value, $meta['canBeNull']);

            $shouldSet = $value !== null
                || $meta['canBeNull']
                || array_key_exists($prop->getName(), $data);

            if ($shouldSet) {
                $this->setValue($prop, $value);
            }
        }
    }

    /**
     * @param string|null $elementKey
     * @param string $propertyName
     * @param array<string, mixed> $data
     * @return mixed
     */
    private function resolveValue(?string $elementKey, string $propertyName, array $data): mixed
    {
        if ($elementKey !== null) {
            return $this->getDataByKey([$elementKey], $data);
        }
        return $data[$propertyName] ?? null;
    }

    private function applyCastDefault(mixed $value, mixed $castDefault): mixed
    {
        if ($castDefault !== null) {
            return $value ?? $castDefault;
        }
        return $value;
    }

    private function applyCastMethodDefault(mixed $value, ?string $castMethodDefault): mixed
    {
        if ($castMethodDefault !== null && $value === null && method_exists($this, $castMethodDefault)) {
            return $this->{$castMethodDefault}();
        }
        return $value;
    }

    private function applyCastMethod(mixed $value, ?string $castMethod): mixed
    {
        if ($castMethod !== null && isset($value) && method_exists($this, $castMethod)) {
            return $this->{$castMethod}($value);
        }
        return $value;
    }

    private function applyCanBeNull(mixed $value, bool $canBeNull): mixed
    {
        if ($canBeNull) {
            return $value ?? null;
        }
        return $value;
    }

    /**
     * @param string $class
     * @return array{properties: list<array{property: ReflectionProperty, elementKey: string|null, castDefault: mixed, castMethodDefault: string|null, castMethod: string|null, canBeNull: bool}>, publicProperties: array<string, ReflectionProperty>}
     */
    private static function getReflectionCache(string $class): array
    {
        if (isset(self::$reflectionCache[$class])) {
            return self::$reflectionCache[$class];
        }

        $ref = new ReflectionClass($class);
        $properties = [];
        $publicProperties = [];

        foreach ($ref->getProperties() as $prop) {
            if ($prop->getDeclaringClass()->getName() === self::class && $prop->getName() === 'errors') {
                continue;
            }
            $meta = [
                'property' => $prop,
                'elementKey' => null,
                'castDefault' => null,
                'castMethodDefault' => null,
                'castMethod' => null,
                'canBeNull' => false,
            ];

            foreach ($prop->getAttributes() as $attribute) {
                $name = $attribute->getName();
                $args = $attribute->getArguments();

                if ($name === ElementName::class && isset($args[0]) && is_string($args[0])) {
                    $meta['elementKey'] = $args[0];
                }
                if ($name === CastDefault::class && isset($args[0])) {
                    $meta['castDefault'] = $args[0];
                }
                if ($name === CastMethodDefault::class && isset($args[0]) && is_string($args[0])) {
                    $meta['castMethodDefault'] = $args[0];
                }
                if ($name === CastMethod::class && isset($args[0]) && is_string($args[0])) {
                    $meta['castMethod'] = $args[0];
                }
                if ($name === CanBeNull::class) {
                    $meta['canBeNull'] = true;
                }
            }

            $properties[] = $meta;
            if ($prop->isPublic()) {
                $publicProperties[$prop->getName()] = $prop;
            }
        }

        self::$reflectionCache[$class] = [
            'properties' => $properties,
            'publicProperties' => $publicProperties,
        ];

        return self::$reflectionCache[$class];
    }

    /**
     * @param ReflectionProperty $prop
     * @param mixed $value
     *
     * @return void
     */
    private function setValue(ReflectionProperty $prop, mixed $value): void
    {
        try {
            $prop->setValue($this, $value);
        } catch (Throwable $exception) {
            $this->errors[$prop->getName()] = $exception->getMessage();
        }
    }

    /**
     * @param array<int, string> $args
     * @param array<string, mixed> $data
     *
     * @return mixed
     */
    private function getDataByKey(array $args, array $data): mixed
    {
        $or = explode('||', $args[0]);
        $value = null;

        foreach ($or as $item) {
            $array = explode('.', $item);

            $value = array_reduce($array, static function ($val, $key) {
                if (!is_array($val)) {
                    return null;
                }
                return $val[$key] ?? null;
            }, $data);

            if ($value) {
                break;
            }
        }

        return $value;
    }

    /**
     * @return array<string, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        return $this->$key;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set(string $key, mixed $value): void
    {
        $this->$key = $value;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return isset($this->$key);
    }
}
