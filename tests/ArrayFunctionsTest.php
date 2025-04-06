<?php

namespace BYanelli\Partials\Tests;

use PHPUnit\Framework\TestCase;

use function BYanelli\Partials\Arrays\p_array_all;
use function BYanelli\Partials\Arrays\p_array_any;
use function BYanelli\Partials\Arrays\p_array_chunk;
use function BYanelli\Partials\Arrays\p_array_column;
use function BYanelli\Partials\Arrays\p_array_diff;
use function BYanelli\Partials\Arrays\p_array_diff_assoc;
use function BYanelli\Partials\Arrays\p_array_diff_key;
use function BYanelli\Partials\Arrays\p_array_filter;
use function BYanelli\Partials\Arrays\p_array_map;
use function BYanelli\Partials\Arrays\p_array_reduce;
use function Psl\Fun\pipe;

class ArrayFunctionsTest extends TestCase
{
    public function test_array_all()
    {
        // Test with numbers (all greater than 0)
        $this->assertTrue(
            pipe(
                p_array_all(fn (int $x) => $x > 0),
            )([1, 2, 3, 4, 5])
        );

        // Test with numbers (contains elements not satisfying the condition)
        $this->assertFalse(
            pipe(
                p_array_all(fn (int $x) => $x > 0),
            )([1, 2, -3, 4, 5])
        );

        // Test with strings (all have length > 3)
        $this->assertTrue(
            pipe(
                p_array_all(fn (string $x) => strlen($x) > 3),
            )(['hello', 'world', 'test'])
        );

        // Test with strings (contains strings with length <= 3)
        $this->assertFalse(
            pipe(
                p_array_all(fn (string $x) => strlen($x) > 3),
            )(['hi', 'world', 'test'])
        );
    }

    public function test_array_any()
    {
        // Test with numbers (at least one greater than 0)
        $this->assertTrue(
            pipe(
                p_array_any(fn (int $x) => $x > 0),
            )([-1, -2, 3, -4, -5])
        );

        // Test with numbers (none greater than 0)
        $this->assertFalse(
            pipe(
                p_array_any(fn (int $x) => $x > 0),
            )([-1, -2, -3, -4, -5])
        );

        // Test with strings (at least one has length > 3)
        $this->assertTrue(
            pipe(
                p_array_any(fn (string $x) => strlen($x) > 3),
            )(['hi', 'world', 'no'])
        );

        // Test with strings (none has length > 3)
        $this->assertFalse(
            pipe(
                p_array_any(fn (string $x) => strlen($x) > 3),
            )(['hi', 'no', 'ok'])
        );
    }

    public function test_array_chunk()
    {
        // Test with default key behavior (keys NOT preserved)
        $this->assertEquals(
            [[1, 2], [3, 4], [5]],
            pipe(
                p_array_chunk(2),
            )([1, 2, 3, 4, 5])
        );

        // Test with preserved keys (should keep original array keys)
        $this->assertEquals(
            [[0 => 1, 1 => 2], [2 => 3, 3 => 4], [4 => 5]],
            pipe(
                p_array_chunk(2, true),
            )([1, 2, 3, 4, 5])
        );

        // Test with chunks larger than the array size
        $this->assertEquals(
            [[1, 2, 3, 4, 5]],
            pipe(
                p_array_chunk(10),
            )([1, 2, 3, 4, 5])
        );

        // Test with smaller array than chunk size
        $this->assertEquals(
            [[1]],
            pipe(
                p_array_chunk(2),
            )([1])
        );

        // Edge case: Empty array
        $this->assertEquals(
            [],
            pipe(
                p_array_chunk(2),
            )([])
        );
    }

    public function test_array_column()
    {
        $testArray = [
            ['id' => 1, 'name' => 'Alice', 'age' => 30],
            ['id' => 2, 'name' => 'Bob', 'age' => 25],
            ['id' => 3, 'name' => 'Charlie', 'age' => 35],
        ];

        // Test extracting a single column without specifying keys
        $this->assertEquals(
            ['Alice', 'Bob', 'Charlie'],
            pipe(
                p_array_column('name'),
            )($testArray)
        );

        // Test extracting a single column while specifying another column as keys
        $this->assertEquals(
            [1 => 'Alice', 2 => 'Bob', 3 => 'Charlie'],
            pipe(
                p_array_column('name', 'id'),
            )($testArray)
        );

        // Test extracting a numeric column
        $this->assertEquals(
            [30, 25, 35],
            pipe(
                p_array_column('age'),
            )($testArray)
        );

        // Test specifying indexKey only (use entire array as values)
        $this->assertEquals(
            [
                1 => ['id' => 1, 'name' => 'Alice', 'age' => 30],
                2 => ['id' => 2, 'name' => 'Bob', 'age' => 25],
                3 => ['id' => 3, 'name' => 'Charlie', 'age' => 35],
            ],
            pipe(
                p_array_column(null, 'id'),
            )($testArray)
        );

        // Edge case: Empty array
        $this->assertEquals(
            [],
            pipe(
                p_array_column('name'),
            )([])
        );
    }

    public function test_array_diff()
    {
        $baseArray = [1, 2, 3, 4, 5];

        // Test with one array to compare
        $this->assertEquals(
            [1, 2],
            pipe(
                p_array_diff([3, 4, 5]),
            )($baseArray)
        );

        // Test with multiple arrays to compare
        $this->assertEquals(
            [1],
            pipe(
                p_array_diff([2, 3], [4, 5]),
            )($baseArray)
        );

        // Test with no difference (all elements are removed)
        $this->assertEquals(
            [],
            pipe(
                p_array_diff([1, 2, 3, 4, 5]),
            )($baseArray)
        );

        // Test with no input arrays to compare (original array is returned)
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            pipe(
                p_array_diff(),
            )($baseArray)
        );

        // Edge case: Empty base array (result is always empty)
        $this->assertEquals(
            [],
            pipe(
                p_array_diff([3, 4, 5]),
            )([])
        );

        // Edge case: Empty comparison arrays (original array is returned)
        $this->assertEquals(
            [1, 2, 3, 4, 5],
            pipe(
                p_array_diff([]),
            )($baseArray)
        );
    }

    public function test_array_diff_assoc()
    {
        $baseArray = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ];

        // Test with key-value difference (removes exact matches)
        $this->assertEquals(
            ['a' => 1],
            pipe(
                p_array_diff_assoc(['b' => 2, 'c' => 3, 'd' => 4]),
            )($baseArray)
        );

        // Test with mismatched keys (differences based on keys only)
        $this->assertEquals(
            [
                'a' => 1,
                'b' => 2,
            ],
            pipe(
                p_array_diff_assoc(['c' => 3, 'd' => 4, 'e' => 5]),
            )($baseArray)
        );

        // Test with multiple arrays to compare
        $this->assertEquals(
            ['a' => 1],
            pipe(
                p_array_diff_assoc(['b' => 2], ['c' => 3, 'd' => 4]),
            )($baseArray)
        );

        // Edge case: No difference (all elements removed)
        $this->assertEquals(
            [],
            pipe(
                p_array_diff_assoc($baseArray),
            )($baseArray)
        );

        // Edge case: Empty base array (result always empty)
        $this->assertEquals(
            [],
            pipe(
                p_array_diff_assoc(['a' => 1, 'b' => 2]),
            )([])
        );

        // Edge case: Empty comparison arrays (original array returned)
        $this->assertEquals(
            $baseArray,
            pipe(
                p_array_diff_assoc([]),
            )($baseArray)
        );
    }

    public function test_array_diff_key()
    {
        $baseArray = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ];

        // Test with one comparison array (removing keys that match)
        $this->assertEquals(
            ['a' => 1, 'd' => 4],
            pipe(
                p_array_diff_key(['b' => 99, 'c' => 50]), // Comparing keys only (values ignored)
            )($baseArray)
        );

        // Test with multiple comparison arrays
        $this->assertEquals(
            ['a' => 1],
            pipe(
                p_array_diff_key(['b' => 99], ['c' => 50, 'd' => 12]),
            )($baseArray)
        );

        // Edge case: No difference (all keys are removed)
        $this->assertEquals(
            [],
            pipe(
                p_array_diff_key($baseArray), // All keys are in the comparison array
            )($baseArray)
        );

        // Edge case: Empty base array (result always empty)
        $this->assertEquals(
            [],
            pipe(
                p_array_diff_key(['b' => 99]),
            )([])
        );

        // Edge case: Empty comparison arrays (original array returned)
        $this->assertEquals(
            $baseArray,
            pipe(
                p_array_diff_key([]),
            )($baseArray)
        );

        // Edge case: Keys in comparison arrays but no intersection
        $this->assertEquals(
            $baseArray,
            pipe(
                p_array_diff_key(['z' => 100, 'y' => 200]), // None of these keys exist in $baseArray
            )($baseArray)
        );
    }

    public function test_array_filter()
    {
        // Case 1: Normal case with multiples of 5 and 10
        $this->assertEquals(
            20,
            // Multiply all numbers by 5 and get the last multiple of 10.
            pipe(
                p_array_map(fn (int $x) => $x * 5),
                p_array_filter(fn (int $x) => ($x % 10) == 0),
                array_pop(...),
            )([3, 4, 5])
        );

        // Case 2: No multiples of 10 after mapping
        $this->assertEquals(
            null,
            // No numbers satisfy the filter condition
            pipe(
                p_array_map(fn (int $x) => $x * 5),
                p_array_filter(fn (int $x) => ($x % 10) == 0),
                array_pop(...),
            )([0, 1])
        );

        // Case 3: Empty array input
        $this->assertEquals(
            null,
            // Input array is empty, result is null
            pipe(
                p_array_map(fn (int $x) => $x * 5),
                p_array_filter(fn (int $x) => ($x % 10) == 0),
                array_pop(...),
            )([])
        );

        // Case 4: Large array with many multiples of 10
        $this->assertEquals(
            100,
            // Multiply all numbers by 5, filter multiples of 10, and retrieve the last one
            pipe(
                p_array_map(fn (int $x) => $x * 5),
                p_array_filter(fn (int $x) => ($x % 10) == 0),
                array_pop(...),
            )(range(1, 20))
        );
    }

    public function test_array_map()
    {
        // Case 1: Simple mapping (baseline)
        $this->assertEquals(
            [5, 10, 15],
            // Maps each number to its value multiplied by 5
            pipe(
                p_array_map(fn ($x) => $x * 5),
            )([1, 2, 3])
        );

        // Case 2: Empty array input (edge case)
        $this->assertEquals(
            [],
            // Maps an empty array to another empty array
            pipe(
                p_array_map(fn ($x) => $x * 5),
            )([])
        );

        // Case 3: Mapping non-integer numbers (handling floats)
        $this->assertEquals(
            [2.5, 5.0, 7.5],
            // Maps floating-point numbers correctly
            pipe(
                p_array_map(fn ($x) => $x * 2.5),
            )([1, 2, 3])
        );

        // Case 4: Negative values
        $this->assertEquals(
            [-5, -10, -15],
            // Works with negative numbers correctly
            pipe(
                p_array_map(fn ($x) => $x * 5),
            )([-1, -2, -3])
        );
    }

    public function test_array_reduce()
    {
        $this->assertEquals(
            15,
            // Sum all numbers in the array
            pipe(
                p_array_reduce(fn ($carry, $item) => $carry + $item, 0),
            )([1, 2, 3, 4, 5])
        );

        $this->assertEquals(
            'hello world!',
            // Concatenate strings in the array
            pipe(
                p_array_reduce(fn ($carry, $item) => $carry.$item, ''),
            )(['hello', ' ', 'world', '!'])
        );
    }
}
