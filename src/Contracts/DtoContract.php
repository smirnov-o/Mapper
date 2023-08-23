<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Contracts;

/**
 * Interface DtoContract
 */
interface DtoContract
{
    /**
     * @param array $data
     * @return $this
     */
    public function init(array $data): static;

    /**
     * @return array
     */
    public function toArray(): array;
}
