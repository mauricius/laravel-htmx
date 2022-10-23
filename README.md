# laravel-htmx

Laravel helper library for [htmx](https://htmx.org/)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mauricius/laravel-htmx.svg?style=flat-square)](https://packagist.org/packages/mauricius/laravel-htmx)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mauricius/laravel-htmx/run-tests?label=tests)](https://github.com/mauricius/laravel-htmx/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mauricius/laravel-htmx.svg?style=flat-square)](https://packagist.org/packages/mauricius/laravel-htmx)

## Installation

You can install the package via composer:

```bash
composer require mauricius/laravel-htmx
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-htmx"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravelHtmx = new LaravelHtmx\LaravelHtmx();
echo $laravelHtmx->echoPhrase('Hello, LaravelHtmx!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [mauricius](https://github.com/mauricius)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
