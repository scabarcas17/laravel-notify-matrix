<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Tests\Stubs\Notifications;

use Illuminate\Notifications\Notification;
use Scabarcas\LaravelNotifyMatrix\Attributes\NotificationGroup;

#[NotificationGroup('orders')]
final class MappedNotification extends Notification
{
    /**
     * @return list<string>
     */
    public function via(mixed $notifiable): array
    {
        return ['mail', 'database'];
    }
}
