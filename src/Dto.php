<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use ReflectionClass;
use SmirnovO\Mapper\Attribute\CastDefault;
use SmirnovO\Mapper\Attribute\CastMethod;
use SmirnovO\Mapper\Attribute\ElementName;
use SmirnovO\Mapper\Contracts\DtoContract;
use Throwable;

use function array_reduce;
use function explode;
use function method_exists;

/**
 * Class Dto
 */
abstract class Dto implements DtoContract
{
    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if ($data !== []) {
            $this->parse($data);
        }
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function init(array $data): static
    {
        $this->parse($data);

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return (array)$this;
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
     * @param array $data
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

            foreach ($attrs as $attribute) {
                if ($attribute->getName() === ElementName::class) {
                    $value = $this->getDataByKey($attribute->getArguments(), $data);
                }

                if ($attribute->getName() === CastDefault::class) {
                    $value = $value ?? $attribute->getArguments()[0];
                }

                if (isset($value) && $attribute->getName() === CastMethod::class) {
                    $cast = $attribute->getArguments()[0];

                    if (method_exists($this, $cast)) {
                        $value = $this->{$cast}($value);
                    }
                }
            }

            if (isset($value)) {
                try {
                    $prop->setValue($this, $value);
                } catch (Throwable) {
                }
            }
        }
    }

    /**
     * @param array $args
     * @param array $data
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
}
