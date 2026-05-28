<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Scabarcas\LaravelNotifyMatrix\Contracts\GroupResolver;
use Scabarcas\LaravelNotifyMatrix\Contracts\PreferenceRepository;
use Scabarcas\LaravelNotifyMatrix\Models\NotificationPreference;

final class PreferenceManager
{
    public function __construct(
        private readonly PreferenceRepository $repository,
        private readonly GroupResolver $resolver,
        private readonly Repository $config,
    ) {
    }

    public function wants(Model $notifiable, string $groupOrClass, string $channel): bool
    {
        $group = $this->isClass($groupOrClass)
            ? $this->resolver->resolve($groupOrClass)
            : $groupOrClass;

        if ($this->isForced($group, $channel)) {
            return true;
        }

        $preference = $this->repository->find($notifiable, $group, $channel);

        if ($preference !== null) {
            return $preference->enabled;
        }

        return $this->defaultPolicyForGroup($group) === 'opt_in';
    }

    public function setPreference(Model $notifiable, string $group, string $channel, bool $enabled): NotificationPreference
    {
        return $this->repository->set($notifiable, $group, $channel, $enabled);
    }

    /**
     * @return Collection<int, NotificationPreference>
     */
    public function preferencesFor(Model $notifiable): Collection
    {
        return $this->repository->forNotifiable($notifiable);
    }

    /**
     * @return Collection<int, NotificationPreference>
     */
    public function preferencesForGroup(Model $notifiable, string $group): Collection
    {
        return $this->repository->forGroup($notifiable, $group);
    }

    public function clear(Model $notifiable, ?string $group = null): int
    {
        return $this->repository->clear($notifiable, $group);
    }

    public function resolveGroup(string $notificationClass): string
    {
        return $this->resolver->resolve($notificationClass);
    }

    private function isClass(string $value): bool
    {
        return str_contains($value, '\\') && class_exists($value);
    }

    private function isForced(string $group, string $channel): bool
    {
        $forced = (array) $this->config->get("notify-matrix.groups.{$group}.forced", []);

        return in_array($channel, $forced, true);
    }

    private function defaultPolicyForGroup(string $group): string
    {
        $groupPolicy = $this->config->get("notify-matrix.groups.{$group}.default_policy");

        if (is_string($groupPolicy)) {
            return $groupPolicy;
        }

        $globalPolicy = $this->config->get('notify-matrix.default_policy', 'opt_in');

        return is_string($globalPolicy) ? $globalPolicy : 'opt_in';
    }
}
