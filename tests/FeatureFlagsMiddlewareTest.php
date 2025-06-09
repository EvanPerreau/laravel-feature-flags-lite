<?php

declare(strict_types=1);

namespace Evanperreau\LaravelFeatureFlagsLite\Tests;

use Evanperreau\LaravelFeatureFlagsLite\Facades\Feature;
use Evanperreau\LaravelFeatureFlagsLite\FeatureFlagsServiceProvider;
use Evanperreau\LaravelFeatureFlagsLite\Middlewares\FeatureFlagsMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Test for the FeatureFlagsMiddleware
 */
class FeatureFlagsMiddlewareTest extends TestCase
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
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Define test routes
        Route::middleware('feature:enabled_feature')->get('/enabled-feature', function () {
            return new Response('Feature is enabled');
        });

        Route::middleware('feature:disabled_feature')->get('/disabled-feature', function () {
            return new Response('Feature is disabled');
        });

        Route::middleware('feature:custom_status_feature,403')->get('/custom-status', function () {
            return new Response('Feature with custom status code');
        });

        // Configure feature flags
        $this->app['config']->set('features', [
            'enabled_feature' => true,
            'disabled_feature' => false,
            'custom_status_feature' => false,
        ]);
    }

    /**
     * Test if middleware allows access when feature flag is enabled
     */
    public function test_middleware_allows_access_when_feature_enabled(): void
    {
        $response = $this->get('/enabled-feature');
        $response->assertStatus(200);
        $response->assertSee('Feature is enabled');
    }

    /**
     * Test if middleware blocks access when feature flag is disabled
     */
    public function test_middleware_blocks_access_when_feature_disabled(): void
    {
        $response = $this->get('/disabled-feature');
        $response->assertStatus(404);
    }

    /**
     * Test if middleware uses custom status code
     */
    public function test_middleware_uses_custom_status_code(): void
    {
        $response = $this->get('/custom-status');
        $response->assertStatus(403);
    }

    /**
     * Test middleware directly with a mock request
     */
    public function test_middleware_handle_method_directly(): void
    {
        $middleware = new FeatureFlagsMiddleware();
        $request = Request::create('/test', 'GET');
        
        // Test with enabled feature
        $this->app['config']->set('features.test_feature', true);
        $response = $middleware->handle($request, function ($req) {
            return new Response('Passed');
        }, 'test_feature');
        
        $this->assertEquals('Passed', $response->getContent());
        
        // Test with disabled feature
        $this->app['config']->set('features.test_feature', false);
        
        try {
            $middleware->handle($request, function ($req) {
                return new Response('Should not reach here');
            }, 'test_feature');
            
            $this->fail('Expected HttpException was not thrown');
        } catch (HttpException $e) {
            $this->assertEquals(404, $e->getStatusCode());
        }
    }

    /**
     * Test middleware with non-existent feature flag
     */
    public function test_middleware_with_non_existent_feature(): void
    {
        // Define a route with a non-existent feature flag
        Route::middleware('feature:non_existent_feature')->get('/non-existent-feature', function () {
            return new Response('This should not be accessible');
        });
        
        $response = $this->get('/non-existent-feature');
        $response->assertStatus(404);
    }
}
