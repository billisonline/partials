# Partials

Partially applied versions of most PHP builtin functions. For use with pipe libraries and/or the upcoming pipe operator.

## Installation

You can install the package via composer:

```bash
composer require byanelli/partials
```

## Usage

Once the pipe operator RFC is accepted:

```php
use function \BYanelli\Partials\Arrays\{p_array_map, p_array_filter};

// Multiply all items by 5 and return the first multiple of 10.
$result = [3, 4, 5]
    |> p_array_map(fn(int $x) => $x * 5)
    |> p_array_filter(fn(int $x) => ($x % 10) == 0)
    |> array_pop(...);

var_dump($result == 20); // true
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bill Yanelli](https://github.com/billisonline)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
