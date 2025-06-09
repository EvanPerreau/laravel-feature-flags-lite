<?php

declare(strict_types=1);

namespace Evanperreau\LaravelFeatureFlagsLite\Tests;

use Evanperreau\LaravelFeatureFlagsLite\Facades\Feature;
use Evanperreau\LaravelFeatureFlagsLite\FeatureFlagsServiceProvider;
use Orchestra\Testbench\TestCase;

use function Evanperreau\LaravelFeatureFlagsLite\feature;

class FeatureFlagsTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            FeatureFlagsServiceProvider::class,
        ];
    }

    /**
     * Test if feature flag is enabled
     */
    public function test_feature_flag_enabled(): void
    {
        $this->app['config']->set('features.test_feature', true);
        $this->assertTrue(Feature::isEnabled('test_feature'));
        $this->assertTrue(feature('test_feature'));
    }

    /**
     * Test if feature flag is disabled
     */
    public function test_feature_flag_disabled(): void
    {
        $this->app['config']->set('features.test_feature', false);
        $this->assertFalse(Feature::isEnabled('test_feature'));
        $this->assertFalse(feature('test_feature'));
    }

    /**
     * Test if non-existent feature flag returns false by default
     */
    public function test_non_existent_feature_flag(): void
    {
        // No configuration set for this feature
        $this->assertFalse(Feature::isEnabled('non_existent_feature'));
        $this->assertFalse(feature('non_existent_feature'));
    }

    /**
     * Test if feature flag with custom default value works
     */
    public function test_feature_flag_with_multiple_flags(): void
    {
        $this->app['config']->set('features', [
            'feature_one' => true,
            'feature_two' => false,
            'feature_three' => true,
        ]);

        $this->assertTrue(Feature::isEnabled('feature_one'));
        $this->assertFalse(Feature::isEnabled('feature_two'));
        $this->assertTrue(Feature::isEnabled('feature_three'));
        $this->assertFalse(Feature::isEnabled('feature_four'));
    }

    /**
     * Test if feature flag configuration is published correctly
     */
    public function test_config_publishing(): void
    {
        // This test verifies that the ServiceProvider correctly registers the config file
        $this->assertArrayHasKey('features', $this->app['config']->all());
    }
}
