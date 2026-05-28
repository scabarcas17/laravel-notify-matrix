<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Event;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\Notifications\MappedNotification;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\Notifications\UnmappedNotification;
use Scabarcas\LaravelNotifyMatrix\Tests\Stubs\User;

beforeEach(function (): void {
    $this->user = User::query()->create(['email' => 'test@example.test']);
});

it('returns true from the event when the user wants the channel', function (): void {
    $result = Event::until(new NotificationSending($this->user, new MappedNotification(), 'mail'));

    expect($result)->toBeTrue();
});

it('returns false from the event when the user opted out', function (): void {
    $this->user->disable('orders', 'mail');

    $result = Event::until(new NotificationSending($this->user, new MappedNotification(), 'mail'));

    expect($result)->toBeFalse();
});

it('returns true for forced channels even when the user opted out', function (): void {
    config()->set('notify-matrix.groups.orders.forced', ['mail']);
    $this->user->disable('orders', 'mail');

    $result = Event::until(new NotificationSending($this->user, new MappedNotification(), 'mail'));

    expect($result)->toBeTrue();
});

it('does not interfere with unmapped notifications', function (): void {
    $result = Event::until(new NotificationSending($this->user, new UnmappedNotification(), 'mail'));

    expect($result)->toBeNull();
});

it('does not interfere when the notifiable does not use the trait', function (): void {
    $other = new class () extends Model {
        use Notifiable;

        protected $table = 'users';

        protected $guarded = [];
    };

    $other->id = 999;

    $result = Event::until(new NotificationSending($other, new MappedNotification(), 'mail'));

    expect($result)->toBeNull();
});
