<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Scabarcas\LaravelNotifyMatrix\Models\NotificationPreference;

interface PreferenceRepository
{
    public function find(Model $notifiable, string $group, string $channel): ?NotificationPreference;

    public function set(Model $notifiable, string $group, string $channel, bool $enabled): NotificationPreference;

    /**
     * @return Collection<int, NotificationPreference>
     */
    public function forNotifiable(Model $notifiable): Collection;

    /**
     * @return Collection<int, NotificationPreference>
     */
    public function forGroup(Model $notifiable, string $group): Collection;

    public function clear(Model $notifiable, ?string $group = null): int;
}
