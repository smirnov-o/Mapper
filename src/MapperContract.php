<?php

declare(strict_types=1);

namespace SmirnovO\Mapper;

/**
 * Interface MapperContract
 */
interface MapperContract
{
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
}
