<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

use SmirnovO\Mapper\Contracts\MapperContract;
use SmirnovO\Mapper\Contracts\MapperObject;
use SmirnovO\Mapper\Internal\PropertyWriter;
use SmirnovO\Mapper\Internal\ValueResolver;

use function is_string;
use function method_exists;

/**
 * Class Mapper
 */
abstract class Mapper implements MapperContract
{
    /**
     * When true, property types are checked before casting; incompatible values are skipped.
     *
     * @var bool
     */
    public bool $strict = false;

    /**
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * @var array<string, string>
     */
    protected array $map;

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $map
     */
    public function __construct(array $data = [], array $map = [])
    {
        $this->map = $map;

        if ($data !== []) {
            $this->parse($data);
        }
    }

    /**
     * @param array<string, mixed> $data
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
     * @return array<string>
     */
    public function getCast(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $data
     * @return void
     */
    private function parse(array $data): void
    {
        $maps = $this->getMap() !== [] ? $this->getMap() : $this->map;

        foreach ($maps as $mapKey => $sourceKey) {
            $value = null;

            if ($sourceKey !== '' && is_string($sourceKey)) {
                $value = ValueResolver::resolve($sourceKey, $data);
            }

            $method = $this->getCast()[$mapKey] ?? null;
            if ($method !== null && method_exists($this, $method)) {
                $value = $this->{$method}($value);
            }

            if ($value !== null) {
                $written = false;
                if (is_subclass_of($this, MapperObject::class)) {
                    $written = PropertyWriter::write($this, $mapKey, $value, $this->strict);
                }

                if (!$written) {
                    $this->data[$mapKey] = $value;
                }
            }
        }
    }
}
