# Changelog

All notable changes to `laravel-htmx` will be documented in this file.

## 0.9.0 - 2025-09-18

### What's Changed

- [Added support for expressing HX-Location using array](https://github.com/mauricius/laravel-htmx/pull/34)
- [Implement missing redirect and refresh methods](https://github.com/mauricius/laravel-htmx/pull/34)
- [Allow to pass the content in the HtmxResponse constructor](https://github.com/mauricius/laravel-htmx/pull/34)

## 0.8.0 - 2025-02-25

### What's Changed

- [Added support for Laravel 12](https://github.com/mauricius/laravel-htmx/pull/24)

## 0.7.0 - 2024-12-24

### What's Changed

- [Added support for PHP 8.4](https://github.com/mauricius/laravel-htmx/pull/23)

## 0.6.0 - 2024-03-19

### What's Changed

- [Added support for Laravel 11 and PHP 8.3](https://github.com/mauricius/laravel-htmx/pull/17)
- Removed support for Laravel 8 and PHP 8.0

## 0.5.0 - 2023-11-19

### What's Changed

- Added support for complex events for HX-Triggers Response Headers

## 0.4.0 - 2023-08-02

### What's Changed

- Added support for nested fragments (requires `ext-mbstring`)

## 0.3.0 - 2023-03-25

### What's Changed

- Added support for Laravel 10

## 0.2.1 - 2022-11-19

### What's Changed

- Improved regex to support fragments enclosed in single and double quotes
- Move configuration publishing to bootForConsole()

## 0.2.0 - 2022-11-06

### What's Changed

- Added support for Laravel 8.80 and higher
- Renamed `fragment` macro to `renderFragment` due to `fragment` making its way into [Laravel's core](https://github.com/laravel/framework/pull/44774).

## 0.1.0 - 2022-11-02

- Initial release
