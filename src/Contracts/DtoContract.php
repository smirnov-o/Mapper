<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Contracts;

/**
 * Interface DtoContract
 */
interface DtoContract
{
    /**
     * @param array<string, mixed> $data
     * @return $this
     */
    public function init(array $data): static;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
