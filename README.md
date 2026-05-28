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

## Quick start

Add the trait to the user model (or any notifiable):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Scabarcas\LaravelNotifyMatrix\Concerns\HasNotificationPreferences;

class User extends Authenticatable
{
    use HasNotificationPreferences;
}
```

Tag each notification with the group it belongs to:

```php
use Illuminate\Notifications\Notification;
use Scabarcas\LaravelNotifyMatrix\Attributes\NotificationGroup;

#[NotificationGroup('orders')]
class OrderShipped extends Notification
{
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }
}
```

Read and write preferences from the model:

```php
$user->wants('orders', 'mail');                // true | false
$user->wants(OrderShipped::class, 'mail');     // resolves the group via the attribute
$user->setPreference('orders', 'mail', false);
$user->enable('orders', 'mail');
$user->disable('orders', 'mail');
$user->getPreferences();
$user->getPreferencesForGroup('orders');
$user->clearPreferences('orders');
```

With the trait on the notifiable and the attribute on the notification, the dispatch listener filters channels according to stored preferences. Forced channels are always delivered.

## Configuration

```php
return [
    'table' => 'notification_preferences',

    // Applied when no preference exists for a channel within a group.
    // Each group may override this below. Supported: "opt_in", "opt_out".
    'default_policy' => 'opt_in',

    'groups' => [
        'marketing' => [
            'default_policy' => 'opt_out',
        ],

        'security' => [
            'default_policy' => 'opt_in',
            'forced'         => ['mail'],
        ],
    ],

    'class_map' => [
        // Map notifications that cannot be annotated directly.
        // \Vendor\Pkg\Notifications\InvoicePaid::class => 'billing',
    ],

    'cache' => [
        'enabled' => true,
        'ttl'     => 300,
        'store'   => null,
    ],
];
```

### Forced channels

Channels listed under `groups.<group>.forced` are delivered even when the user has opted out. Common use cases are security alerts and account verification messages.

### Class map

Third-party notification classes that cannot be annotated with `#[NotificationGroup]` can be mapped to a group through the `class_map` entry. Annotated classes always take precedence over the map.

## How it works

The package registers a listener for `Illuminate\Notifications\Events\NotificationSending` that runs before each channel dispatch:

1. If the notifiable does not use `HasNotificationPreferences`, the listener does not interfere.
2. If the notification has neither a `#[NotificationGroup]` attribute nor a `class_map` entry, the listener does not interfere.
3. If the channel is listed as forced for the group, the channel is delivered.
4. If the user has a stored preference for the channel, that value decides.
5. Otherwise, the group default policy decides (or the global default if the group has none).

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
