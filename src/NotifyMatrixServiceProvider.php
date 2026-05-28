<?php

declare(strict_types=1);

namespace Scabarcas\LaravelNotifyMatrix;

use Illuminate\Support\ServiceProvider;
use Scabarcas\LaravelNotifyMatrix\Contracts\GroupResolver;
use Scabarcas\LaravelNotifyMatrix\Contracts\PreferenceRepository;
use Scabarcas\LaravelNotifyMatrix\Repositories\EloquentPreferenceRepository;
use Scabarcas\LaravelNotifyMatrix\Resolvers\AttributeGroupResolver;

class NotifyMatrixServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/notify-matrix.php',
            'notify-matrix',
        );

        $this->app->singleton(PreferenceRepository::class, EloquentPreferenceRepository::class);
        $this->app->singleton(GroupResolver::class, AttributeGroupResolver::class);
        $this->app->singleton(PreferenceManager::class);
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
