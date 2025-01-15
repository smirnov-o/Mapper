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

use function array_reduce;
use function explode;
use function is_string;
use function md5;
use function method_exists;
use function spl_object_id;

/**
 * Class Dto
 */
abstract class Dto implements DtoContract
{
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
        $ref = new ReflectionClass($this);
        $properties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $array = [];

        foreach ($properties as $property) {
            $name = $property->getName();

            try {
                $array[$name] = $property->getValue($this);
            }catch (Throwable){}
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
        $ref = new ReflectionClass(static::class);
        $props = $ref->getProperties();

        foreach ($props as $prop) {
            $attrs = $prop->getAttributes();
            $value = null;
            $noCastSet = true;

            foreach ($attrs as $attribute) {
                if ($attribute->getName() === ElementName::class) {
                    $value = $this->getDataByKey($attribute->getArguments(), $data);
                }

                if ($noCastSet && $attribute->getName() === CastDefault::class) {
                    $value = $value ?? $attribute->getArguments()[0];
                    $noCastSet = false;
                }

                if ($noCastSet && !isset($value) && ($attribute->getName() === CastMethodDefault::class)) {
                    $cast = $attribute->getArguments()[0];

                    if (is_string($cast) && method_exists($this, $cast)) {
                        $value = $this->{$cast}();
                        $noCastSet = false;
                    }
                }

                if ($noCastSet && isset($value) && $attribute->getName() === CastMethod::class) {
                    $cast = $attribute->getArguments()[0];

                    if (is_string($cast) && method_exists($this, $cast)) {
                        $value = $this->{$cast}($value);
                        $noCastSet = false;
                    }
                }

                if ($noCastSet && $attribute->getName() === CanBeNull::class) {
                    $value      = $value ?? null;
                    $noCastSet = false;
                }
            }

            if (! $noCastSet) {
                $this->setValue($prop, $value);
                continue;
            }

            if (isset($value)) {
                $this->setValue($prop, $value);
            }
        }
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
            if(! isset($this->{$this->getHash()})) {
                $this->{$this->getHash()} = [];
            }
            $this->{$this->getHash()}[$prop->getName()] = $exception->getMessage();
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
                return $val[$key] ?? null;
            }, $data);

            if ($value) {
                break;
            }
        }

        return $value;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->{$this->getHash()};
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

    /**
     * @return string
     */
    private function getHash(): string
    {
        return md5((string)spl_object_id($this));
    }
}
