<?php

declare(strict_types=1);

namespace Evanperreau\LaravelFeatureFlagsLite;

if (!function_exists('feature')) {
    /**
     * Check if a feature is enabled.
     *
     * @param string $key The feature to check
     * @return bool True if the feature is enabled, false otherwise
     */
    function feature(string $key): bool
    {
        return app('featureflags')->isEnabled($key);
    }
}
