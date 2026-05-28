<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Resolvers;

use Illuminate\Contracts\Config\Repository;
use ReflectionClass;
use Scabarcas\LaravelNotifyMatrix\Attributes\NotificationGroup;
use Scabarcas\LaravelNotifyMatrix\Contracts\GroupResolver;
use Scabarcas\LaravelNotifyMatrix\Exceptions\UnknownNotificationGroupException;

final class AttributeGroupResolver implements GroupResolver
{
    public function __construct(
        private readonly Repository $config,
    ) {
    }

    public function resolve(string $notificationClass): string
    {
        if (!class_exists($notificationClass)) {
            throw UnknownNotificationGroupException::forClass($notificationClass);
        }

        $attributes = (new ReflectionClass($notificationClass))
            ->getAttributes(NotificationGroup::class);

        if ($attributes !== []) {
            return $attributes[0]->newInstance()->name;
        }

        $map = (array) $this->config->get('notify-matrix.class_map', []);

        if (isset($map[$notificationClass]) && is_string($map[$notificationClass])) {
            return $map[$notificationClass];
        }

        throw UnknownNotificationGroupException::forClass($notificationClass);
    }
}
