<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'group',
        'channel',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function getTable(): string
    {
        return (string) config('notify-matrix.table', 'notification_preferences');
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
