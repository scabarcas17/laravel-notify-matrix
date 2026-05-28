<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Listeners;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Events\NotificationSending;
use Scabarcas\LaravelNotifyMatrix\Concerns\HasNotificationPreferences;
use Scabarcas\LaravelNotifyMatrix\Exceptions\UnknownNotificationGroupException;
use Scabarcas\LaravelNotifyMatrix\PreferenceManager;

final class EnforcePreferences
{
    public function __construct(
        private readonly PreferenceManager $manager,
    ) {
    }

    public function handle(NotificationSending $event): ?bool
    {
        $notifiable = $event->notifiable;

        if (!$notifiable instanceof Model || !$this->notifiableUsesTrait($notifiable)) {
            return null;
        }

        try {
            $group = $this->manager->resolveGroup($event->notification::class);
        } catch (UnknownNotificationGroupException) {
            return null;
        }

        return $this->manager->wants($notifiable, $group, $event->channel);
    }

    private function notifiableUsesTrait(Model $notifiable): bool
    {
        return in_array(
            HasNotificationPreferences::class,
            class_uses_recursive($notifiable),
            true,
        );
    }
}
