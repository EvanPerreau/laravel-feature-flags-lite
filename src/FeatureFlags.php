<?php

declare(strict_types=1);

namespace Evanperreau\LaravelFeatureFlagsLite;

use Illuminate\Support\Facades\Config;

class FeatureFlags
{
    /**
     * Check if a feature is enabled.
     *
     * @param string $feature The feature to check
     * @return bool True if the feature is enabled, false otherwise
     */
    public function isEnabled(string $feature): bool
    {
        return Config::get("features.$feature", false);
    }
}
