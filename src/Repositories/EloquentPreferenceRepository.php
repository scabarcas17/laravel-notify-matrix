<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Scabarcas\LaravelNotifyMatrix\Contracts\PreferenceRepository;
use Scabarcas\LaravelNotifyMatrix\Models\NotificationPreference;

final class EloquentPreferenceRepository implements PreferenceRepository
{
    public function find(Model $notifiable, string $group, string $channel): ?NotificationPreference
    {
        return $this->queryFor($notifiable)
            ->where('group', $group)
            ->where('channel', $channel)
            ->first();
    }

    public function set(Model $notifiable, string $group, string $channel, bool $enabled): NotificationPreference
    {
        return NotificationPreference::query()->updateOrCreate(
            [
                'notifiable_type' => $notifiable->getMorphClass(),
                'notifiable_id'   => $notifiable->getKey(),
                'group'           => $group,
                'channel'         => $channel,
            ],
            [
                'enabled' => $enabled,
            ],
        );
    }

    public function forNotifiable(Model $notifiable): Collection
    {
        return $this->queryFor($notifiable)->get();
    }

    public function forGroup(Model $notifiable, string $group): Collection
    {
        return $this->queryFor($notifiable)
            ->where('group', $group)
            ->get();
    }

    public function clear(Model $notifiable, ?string $group = null): int
    {
        $query = $this->queryFor($notifiable);

        if ($group !== null) {
            $query->where('group', $group);
        }

        return $query->delete();
    }

    /**
     * @return Builder<NotificationPreference>
     */
    private function queryFor(Model $notifiable): Builder
    {
        return NotificationPreference::query()
            ->where('notifiable_type', $notifiable->getMorphClass())
            ->where('notifiable_id', $notifiable->getKey());
    }
}
