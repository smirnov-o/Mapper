<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use ReflectionClass;
use ReflectionException;

use function array_reduce;
use function explode;
use function is_string;
use function settype;

/**
 * Class Mapper
 */
abstract class Mapper implements MapperContract
{
    /**
     * @var array<string, mixed>
     */
    private array $data = [];

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
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getCast(): array
    {
        return [];
    }

    /**
     * @param array $data
     * @return void
     */
    private function parse(array $data): void
    {
        $value = null;

        foreach ($this->getMap() as $key => $map) {
            if ($key && is_string($key)) {
                $value = $this->getDataByKey($key, $data);
            }

            if ($value) {
                if (is_subclass_of($this, MapperObject::class)) {
                    $this->setVariable($map, $value);
                }

                $this->data[$map] = $value;
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function setVariable(string $key, mixed $value): void
    {
        $ref = new ReflectionClass(static::class);
        $prop = null;

        try {
            $prop = $ref->getProperty($key);
        } catch (ReflectionException) {
        }

        if ($prop) {
            $result = true;
            $type = $prop->getType()?->getName();

            if ($type) {
                $result = settype($value, $type);
            }

            if ($result) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @param string $key
     * @param array $data
     * @return mixed
     */
    private function getDataByKey(string $key, array $data): mixed
    {
        $array = explode('.', $key);

        return array_reduce($array, static function ($item, $key) {
            return $item[$key] ?? null;
        }, $data);
    }
}
