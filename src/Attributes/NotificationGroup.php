<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class NotificationGroup
{
    public function __construct(
        public readonly string $name,
    ) {}
}
