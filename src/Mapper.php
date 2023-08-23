<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use ReflectionClass;
use ReflectionException;
use SmirnovO\Mapper\Contracts\MapperContract;
use SmirnovO\Mapper\Contracts\MapperObject;

use function array_reduce;
use function explode;
use function in_array;
use function is_string;
use function method_exists;
use function settype;

/**
 * Class Mapper
 */
abstract class Mapper implements MapperContract
{
    /**
     * @var array
     */
    public const TYPE = ['boolean', 'bool', 'integer', 'int', 'float', 'double', 'string', 'array', 'object', 'null'];

    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @var mixed
     */
    private mixed $empty = null;

    /**
     * @var array
     */
    protected array $map;

    /**
     * @param array $data
     * @param array $map
     */
    public function __construct(array $data = [], array $map = [])
    {
        $this->map = $map;

        if ($data !== []) {
            $this->parse($data);
        }
    }

    /**
     * @param array $data
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
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return (bool)$this->empty;
    }

    /**
     * @param array $data
     * @return void
     */
    private function parse(array $data): void
    {
        $value = null;
        $prop = false;
        $maps = $this->getMap() !== [] ? $this->getMap() : $this->map;

        foreach ($maps as $map => $key) {
            if ($key && is_string($key)) {
                $value = $this->getDataByKey($key, $data);
            }

            $method = $this->getCast()[$map] ?? null;

            if ($method && method_exists($this, $method)) {
                $value = $this->{$method}($value);
            }

            if ($value) {
                $this->empty = $value;

                if (is_subclass_of($this, MapperObject::class)) {
                    $prop = $this->setVariable($map, $value);
                }

                if (!$prop) {
                    $this->data[$map] = $value;
                }
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    private function setVariable(string $key, mixed $value): bool
    {
        $ref = new ReflectionClass($this);
        $prop = null;
        $result = true;

        try {
            $prop = $ref->getProperty($key);
        } catch (ReflectionException) {
            $result = false;
        }

        if ($prop) {
            $type = $prop->getType()?->getName();

            if ($type && in_array($type, self::TYPE, true)) {
                $result = settype($value, $type);
            }

            if ($result) {
                $this->{$key} = $value;
            }
        }

        return $result;
    }

    /**
     * @param string $key
     * @param array $data
     * @return mixed
     */
    private function getDataByKey(string $key, array $data): mixed
    {
        $or = explode('||', $key);
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
