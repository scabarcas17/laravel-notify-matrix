<?php

declare(strict_types=1);

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Scabarcas\LaravelNotifyMatrix\Contracts\GroupResolver;
use Scabarcas\LaravelNotifyMatrix\Contracts\PreferenceRepository;
use Scabarcas\LaravelNotifyMatrix\Models\NotificationPreference;
use Scabarcas\LaravelNotifyMatrix\PreferenceManager;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\Notifications\MappedNotification;

beforeEach(function (): void {
    $this->repository = Mockery::mock(PreferenceRepository::class);
    $this->resolver = Mockery::mock(GroupResolver::class);
    $this->config = Mockery::mock(Repository::class);
    $this->manager = new PreferenceManager($this->repository, $this->resolver, $this->config);
    $this->notifiable = Mockery::mock(Model::class);
});

afterEach(function (): void {
    Mockery::close();
});

it('returns true when the channel is forced for the group', function (): void {
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.security.forced', [])
        ->andReturn(['mail']);

    expect($this->manager->wants($this->notifiable, 'security', 'mail'))->toBeTrue();
});

it('returns the stored preference value when a row exists', function (): void {
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.orders.forced', [])
        ->andReturn([]);

    $pref = new NotificationPreference(['enabled' => false]);
    $this->repository->shouldReceive('find')
        ->with($this->notifiable, 'orders', 'mail')
        ->andReturn($pref);

    expect($this->manager->wants($this->notifiable, 'orders', 'mail'))->toBeFalse();
});

it('uses the group default policy when no preference is stored', function (): void {
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.marketing.forced', [])
        ->andReturn([]);
    $this->repository->shouldReceive('find')
        ->with($this->notifiable, 'marketing', 'mail')
        ->andReturn(null);
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.marketing.default_policy')
        ->andReturn('opt_out');

    expect($this->manager->wants($this->notifiable, 'marketing', 'mail'))->toBeFalse();
});

it('falls back to the global default policy when the group has none', function (): void {
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.orders.forced', [])
        ->andReturn([]);
    $this->repository->shouldReceive('find')
        ->with($this->notifiable, 'orders', 'mail')
        ->andReturn(null);
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.orders.default_policy')
        ->andReturn(null);
    $this->config->shouldReceive('get')
        ->with('notify-matrix.default_policy', 'opt_in')
        ->andReturn('opt_in');

    expect($this->manager->wants($this->notifiable, 'orders', 'mail'))->toBeTrue();
});

it('resolves the group via the resolver when input is a class FQN', function (): void {
    $this->resolver->shouldReceive('resolve')
        ->with(MappedNotification::class)
        ->andReturn('orders');
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.orders.forced', [])
        ->andReturn([]);
    $this->repository->shouldReceive('find')
        ->with($this->notifiable, 'orders', 'mail')
        ->andReturn(null);
    $this->config->shouldReceive('get')
        ->with('notify-matrix.groups.orders.default_policy')
        ->andReturn(null);
    $this->config->shouldReceive('get')
        ->with('notify-matrix.default_policy', 'opt_in')
        ->andReturn('opt_in');

    expect($this->manager->wants($this->notifiable, MappedNotification::class, 'mail'))->toBeTrue();
});

it('delegates setPreference to the repository', function (): void {
    $expected = new NotificationPreference(['enabled' => true]);

    $this->repository->shouldReceive('set')
        ->with($this->notifiable, 'orders', 'mail', true)
        ->andReturn($expected);

    expect($this->manager->setPreference($this->notifiable, 'orders', 'mail', true))->toBe($expected);
});

it('delegates clear to the repository with a group', function (): void {
    $this->repository->shouldReceive('clear')
        ->with($this->notifiable, 'orders')
        ->andReturn(3);

    expect($this->manager->clear($this->notifiable, 'orders'))->toBe(3);
});

it('delegates clear to the repository without a group', function (): void {
    $this->repository->shouldReceive('clear')
        ->with($this->notifiable, null)
        ->andReturn(5);

    expect($this->manager->clear($this->notifiable))->toBe(5);
});
