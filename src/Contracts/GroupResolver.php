<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Contracts;

interface GroupResolver
{
    /**
     * Resolve the preference group a notification class belongs to.
     *
     * @throws \Scabarcas\LaravelNotifyMatrix\Exceptions\UnknownNotificationGroupException
     */
    public function resolve(string $notificationClass): string;
}
