<?php

namespace Evanperreau\LaravelFeatureFlagsLite\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Evanperreau\LaravelFeatureFlagsLite\Facades\Feature;

/**
 * Feature Flags Middleware
 *
 * This middleware allows you to restrict access to routes based on feature flags.
 * If the specified feature is not enabled, the request will be aborted with a 404 response.
 */
class FeatureFlagsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $feature The feature flag to check
     * @param  int  $statusCode The status code to return if the feature is disabled (default: 404)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $feature, int $statusCode = 404): mixed
    {
        if (! Feature::isEnabled($feature)) {
            return abort($statusCode);
        }

        return $next($request);
    }
}
