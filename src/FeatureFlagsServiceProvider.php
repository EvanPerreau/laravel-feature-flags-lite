<?php

declare(strict_types=1);

namespace Evanperreau\LaravelFeatureFlagsLite;

use Illuminate\Support\ServiceProvider;

class FeatureFlagsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('featureflags', function () {
            return new FeatureFlags();
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/features.php',
            'features'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/features.php' => config_path('features.php'),
        ], 'config');

        $this->loadHelpers();
    }

    /**
     * Load helpers.
     *
     * @return void
     */
    protected function loadHelpers(): void
    {
        require_once __DIR__ . '/helpers.php';
    }
}
