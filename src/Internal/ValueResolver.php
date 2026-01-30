<?php

declare(strict_types=1);

namespace SmirnovO\Mapper\Internal;

use function array_reduce;
use function explode;
use function is_array;

/**
 * Resolves value from nested array by dot path or OR-separated paths.
 */
final class ValueResolver
{
    /**
     * @param string $key Key path, e.g. "a.b.c" or "a||b.c"
     * @param array<string, mixed> $data
     * @return mixed
     */
    public static function resolve(string $key, array $data): mixed
    {
        $or = explode('||', $key);
        $value = null;

        foreach ($or as $item) {
            $parts = explode('.', trim($item));

            $value = array_reduce($parts, static function ($val, $part) {
                if (!is_array($val)) {
                    return null;
                }
                return $val[$part] ?? null;
            }, $data);

            if ($value) {
                break;
            }
        }

        return $value;
    }
}
