<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int        $id
 * @property string     $notifiable_type
 * @property int|string $notifiable_id
 * @property string     $group
 * @property string     $channel
 * @property bool       $enabled
 */
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
        $table = config('notify-matrix.table', 'notification_preferences');

        return is_string($table) ? $table : 'notification_preferences';
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
