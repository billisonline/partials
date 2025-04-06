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
