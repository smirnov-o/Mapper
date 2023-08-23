<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Contracts;

/**
 * Interface MapperContract
 */
interface MapperContract
{
    /**
     * @param array $data
     * @return $this
     */
    public function init(array $data): static;

    /**
     * @return array<string, string>
     */
    public function getMap(): array;

    /**
     * @return array<string, mixed>
     */
    public function getData(): array;

    /**
     * @return array<string, string>
     */
    public function getCast(): array;

    /**
     * @return bool
     */
    public function isNotEmpty(): bool;
}
