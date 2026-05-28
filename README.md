# Laravel Notify Matrix

[![CI](https://github.com/scabarcas17/laravel-notify-matrix/actions/workflows/ci.yml/badge.svg)](https://github.com/scabarcas17/laravel-notify-matrix/actions/workflows/ci.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/scabarcas/laravel-notify-matrix.svg)](https://packagist.org/packages/scabarcas/laravel-notify-matrix)
[![Total Downloads](https://img.shields.io/packagist/dt/scabarcas/laravel-notify-matrix.svg)](https://packagist.org/packages/scabarcas/laravel-notify-matrix)
[![PHP Version](https://img.shields.io/packagist/php-v/scabarcas/laravel-notify-matrix.svg)](https://packagist.org/packages/scabarcas/laravel-notify-matrix)
[![License](https://img.shields.io/packagist/l/scabarcas/laravel-notify-matrix.svg)](https://github.com/scabarcas17/laravel-notify-matrix/blob/main/LICENSE)

Manage per-user notification preferences in Laravel. Each user can opt in or out of channels for each notification group.

## Installation

```bash
composer require scabarcas/laravel-notify-matrix
```

```bash
php artisan vendor:publish --tag=notify-matrix-config
php artisan vendor:publish --tag=notify-matrix-migrations
php artisan migrate
```

## Testing

```bash
composer install
composer test
composer analyse
composer format
```

## Author

**Sebastian Cabarcas Berrio** · <sebastianberrio45@hotmail.com> · [@scabarcas17](https://github.com/scabarcas17)

## License

MIT © Sebastian Cabarcas Berrio
