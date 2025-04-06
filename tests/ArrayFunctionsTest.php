<?php

namespace BYanelli\Partials\Tests;

use PHPUnit\Framework\TestCase;
use function BYanelli\Partials\Arrays\{p_array_map, p_array_filter};
use function Psl\Fun\pipe;

class ArrayFunctionsTest extends TestCase
{
    public function testArrayMap() {
        $this->assertEquals(
            [5, 10, 15],
            pipe(
                p_array_map(fn($x) => $x*5),
            )([1, 2, 3])
        );
    }

    public function testArrayFilter() {
        $this->assertEquals(
            20,
            // Multiply all numbers in the array by 5 and get the first multiple of 10.
            pipe(
                p_array_map(fn(int $x) => $x * 5),
                p_array_filter(fn(int $x) => ($x % 10) == 0),
                array_pop(...),
            )([3, 4, 5])
        );
    }
}
