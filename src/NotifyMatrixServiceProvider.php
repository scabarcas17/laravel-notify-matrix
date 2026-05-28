<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix;

use Illuminate\Support\ServiceProvider;

class NotifyMatrixServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/notify-matrix.php',
            'notify-matrix',
        );
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/notify-matrix.php' => config_path('notify-matrix.php'),
            ], 'notify-matrix-config');

            $this->publishesMigrations([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'notify-matrix-migrations');
        }
    }
}
