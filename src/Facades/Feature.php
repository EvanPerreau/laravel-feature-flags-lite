<?php

declare(strict_types=1);

namespace Evanperreau\LaravelFeatureFlagsLite\Facades;

use Illuminate\Support\Facades\Facade;

class Feature extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'featureflags';
    }
}
