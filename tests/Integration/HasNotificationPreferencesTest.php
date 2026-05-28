<?php

declare(strict_types=1);

use Scabarcas\LaravelNotifyMatrix\Models\NotificationPreference;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\User;

beforeEach(function (): void {
    $this->user = User::query()->create(['email' => 'test@example.test']);
});

it('persists a preference via setPreference', function (): void {
    $this->user->setPreference('orders', 'mail', false);

    $stored = NotificationPreference::query()->first();

    expect($stored)->not->toBeNull()
        ->and($stored->group)->toBe('orders')
        ->and($stored->channel)->toBe('mail')
        ->and($stored->enabled)->toBeFalse();
});

it('returns true for opt_in default when no preference exists', function (): void {
    expect($this->user->wants('orders', 'mail'))->toBeTrue();
});

it('returns false when the user has explicitly disabled the channel', function (): void {
    $this->user->disable('orders', 'mail');

    expect($this->user->wants('orders', 'mail'))->toBeFalse();
});

it('returns true for forced channels regardless of explicit opt-out', function (): void {
    config()->set('notify-matrix.groups.security.forced', ['mail']);

    $this->user->disable('security', 'mail');

    expect($this->user->wants('security', 'mail'))->toBeTrue();
});

it('respects the per-group opt_out default policy', function (): void {
    config()->set('notify-matrix.groups.marketing.default_policy', 'opt_out');

    expect($this->user->wants('marketing', 'mail'))->toBeFalse();
});

it('chains setPreference fluently', function (): void {
    $result = $this->user->enable('orders', 'mail')->disable('orders', 'database');

    expect($result)->toBe($this->user);
    expect($this->user->wants('orders', 'mail'))->toBeTrue();
    expect($this->user->wants('orders', 'database'))->toBeFalse();
});

it('exposes preferences as a morph relation', function (): void {
    $this->user->setPreference('orders', 'mail', true);

    expect($this->user->preferences)->toHaveCount(1);
});

it('clears preferences scoped by group', function (): void {
    $this->user->disable('orders', 'mail');
    $this->user->disable('marketing', 'mail');

    $deleted = $this->user->clearPreferences('orders');

    expect($deleted)->toBe(1);
    expect(NotificationPreference::query()->count())->toBe(1);
});

it('clears all preferences when no group is given', function (): void {
    $this->user->disable('orders', 'mail');
    $this->user->disable('marketing', 'mail');

    $deleted = $this->user->clearPreferences();

    expect($deleted)->toBe(2);
    expect(NotificationPreference::query()->count())->toBe(0);
});

it('returns Collection from getPreferencesForGroup', function (): void {
    $this->user->disable('orders', 'mail');
    $this->user->disable('orders', 'database');
    $this->user->disable('marketing', 'mail');

    $collection = $this->user->getPreferencesForGroup('orders');

    expect($collection)->toHaveCount(2);
});
