<?php

declare(strict_types=1);

use Illuminate\Contracts\Config\Repository;
use Scabarcas\LaravelNotifyMatrix\Exceptions\UnknownNotificationGroupException;
use Scabarcas\LaravelNotifyMatrix\Resolvers\AttributeGroupResolver;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\Notifications\MappedNotification;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\Notifications\UnmappedNotification;

afterEach(function (): void {
    Mockery::close();
});

it('resolves the group from the NotificationGroup attribute', function (): void {
    $config = Mockery::mock(Repository::class);
    $resolver = new AttributeGroupResolver($config);

    expect($resolver->resolve(MappedNotification::class))->toBe('orders');
});

it('falls back to the class_map when the attribute is missing', function (): void {
    $config = Mockery::mock(Repository::class);
    $config->shouldReceive('get')
        ->with('notify-matrix.class_map', [])
        ->andReturn([UnmappedNotification::class => 'fallback']);

    $resolver = new AttributeGroupResolver($config);

    expect($resolver->resolve(UnmappedNotification::class))->toBe('fallback');
});

it('throws when neither attribute nor class_map entry exists', function (): void {
    $config = Mockery::mock(Repository::class);
    $config->shouldReceive('get')
        ->with('notify-matrix.class_map', [])
        ->andReturn([]);

    $resolver = new AttributeGroupResolver($config);

    $resolver->resolve(UnmappedNotification::class);
})->throws(UnknownNotificationGroupException::class);

it('prefers the attribute over a conflicting class_map entry', function (): void {
    $config = Mockery::mock(Repository::class);
    $resolver = new AttributeGroupResolver($config);

    expect($resolver->resolve(MappedNotification::class))->toBe('orders');
});
