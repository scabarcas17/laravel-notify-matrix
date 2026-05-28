<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Scabarcas\LaravelNotifyMatrix\Models\NotificationPreference;
use Scabarcas\LaravelNotifyMatrix\PreferenceManager;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasNotificationPreferences
{
    public function preferences(): MorphMany
    {
        return $this->morphMany(NotificationPreference::class, 'notifiable');
    }

    public function wants(string $groupOrClass, string $channel): bool
    {
        return $this->preferenceManager()->wants($this, $groupOrClass, $channel);
    }

    public function setPreference(string $group, string $channel, bool $enabled): static
    {
        $this->preferenceManager()->setPreference($this, $group, $channel, $enabled);

        return $this;
    }

    public function enable(string $group, string $channel): static
    {
        return $this->setPreference($group, $channel, true);
    }

    public function disable(string $group, string $channel): static
    {
        return $this->setPreference($group, $channel, false);
    }

    /**
     * @return Collection<int, NotificationPreference>
     */
    public function getPreferences(): Collection
    {
        return $this->preferenceManager()->preferencesFor($this);
    }

    /**
     * @return Collection<int, NotificationPreference>
     */
    public function getPreferencesForGroup(string $group): Collection
    {
        return $this->preferenceManager()->preferencesForGroup($this, $group);
    }

    public function clearPreferences(?string $group = null): int
    {
        return $this->preferenceManager()->clear($this, $group);
    }

    private function preferenceManager(): PreferenceManager
    {
        return app(PreferenceManager::class);
    }
}
