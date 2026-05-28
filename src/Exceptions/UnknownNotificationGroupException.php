<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Exceptions;

use RuntimeException;

class UnknownNotificationGroupException extends RuntimeException
{
    public static function forClass(string $class): self
    {
        return new self(sprintf(
            'Notification [%s] is not mapped to a preference group. Add the #[NotificationGroup] attribute to the class or register it in notify-matrix.class_map.',
            $class,
        ));
    }
}
