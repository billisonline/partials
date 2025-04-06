<?php

namespace BYanelli\Partials\Arrays;

/**
 * @param callable $callback
 * @return callable(array): array
 */
function p_array_map(callable $callback): callable {
    return function (array $array) use ($callback): array {
        return array_map($callback, $array);
    };
}

/**
 * @param callable $callback
 * @return callable(array): array
 */
function p_array_filter(callable $callback): callable {
    return function (array $array) use ($callback): array {
        return array_filter($array, $callback);
    };
}

/**
 * @template TInitial
 * @param callable $callback
 * @param TInitial $initial
 * @return callable(array): TInitial
 */
function p_array_reduce(callable $callback, $initial): callable {
    return function (array $array) use ($callback, $initial) {
        return array_reduce($array, $callback, $initial);
    };
}

/**
 * @param callable $callback
 * @return callable(array): bool
 * @noinspection PhpLoopCanBeConvertedToArrayAnyInspection
 */
function p_array_all(callable $callback): callable {
    if (function_exists('array_all')) {
        return function (array $array) use ($callback): bool {
            return array_all($array, $callback);
        };
    }

    // Fallback for PHP versions prior to 8.4
    return function (array $array) use ($callback): bool {
        /** @noinspection PhpLoopCanBeConvertedToArrayAllInspection */
        foreach ($array as $value) {
            if (!$callback($value)) {
                return false;
            }
        }
        return true;
    };
}

/**
 * @param callable $callback
 * @return callable(array): bool
 * @noinspection PhpLoopCanBeConvertedToArrayAnyInspection
 */
function p_array_any(callable $callback): callable {
    if (function_exists('array_any')) {
        return function (array $array) use ($callback): bool {
            return array_any($array, $callback);
        };
    }

    // Fallback for PHP versions prior to 8.4
    return function (array $array) use ($callback): bool {
        foreach ($array as $value) {
            if ($callback($value)) {
                return true;
            }
        }
        return false;
    };
}

/**
 * @param int $size
 * @param bool $preserveKeys
 * @return callable(array): array
 */
function p_array_chunk(int $size, bool $preserveKeys = false): callable {
    return function (array $array) use ($size, $preserveKeys): array {
        return array_chunk($array, $size, $preserveKeys);
    };
}

/**
 * @param int|string|null $columnKey
 * @param int|string|null $indexKey
 * @return callable(array): array
 */
function p_array_column(int|string|null $columnKey, int|string|null $indexKey = null): callable {
    return function (array $array) use ($columnKey, $indexKey): array {
        return array_column($array, $columnKey, $indexKey);
    };
}

/**
 * @param array ...$arrays
 * @return callable(array): array
 */
function p_array_diff(array ...$arrays): callable {
    return function (array $array) use ($arrays): array {
        return array_diff($array, ...$arrays);
    };
}

/**
 * @param array ...$arrays
 * @return callable(array): array
 */
function p_array_diff_assoc(array ...$arrays): callable {
    return function (array $array) use ($arrays): array {
        return array_diff_assoc($array, ...$arrays);
    };
}

/**
 * @param array ...$arrays
 * @return callable(array): array
 */
function p_array_diff_key(array ...$arrays): callable {
    return function (array $array) use ($arrays): array {
        return array_diff_key($array, ...$arrays);
    };
}
