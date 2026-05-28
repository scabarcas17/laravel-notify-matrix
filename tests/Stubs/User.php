<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Scabarcas\LaravelNotifyMatrix\Concerns\HasNotificationPreferences;

class User extends Model
{
    use HasNotificationPreferences;
    use Notifiable;

    protected $table = 'users';

    protected $guarded = [];
}
