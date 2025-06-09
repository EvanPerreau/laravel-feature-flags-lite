# Laravel Feature Flags Lite

> A lightweight and simple solution to manage feature flags in your Laravel applications.

## 📋 Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Best Practices](#best-practices)
- [Contributing](#contributing)
- [License](#license)

## 🚀 Installation

You can install the package via composer:

```bash
composer require evanperreau/laravel-feature-flags-lite
```

## ⚙️ Configuration

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Evanperreau\LaravelFeatureFlagsLite\FeatureFlagsServiceProvider" --tag="config"
```

This will create a `features.php` file in your config folder with the following structure:

```php
<?php

return [
    /*
     * Feature flags
     *
     * 'feature_name' => true,
     */
];
```

## 🔧 Usage

### Configuring feature flags

Add your feature flags in the `config/features.php` configuration file:

```php
<?php

return [
    'new_ui' => true,
    'beta_feature' => false,
    'premium_feature' => true,
];
```

### Checking if a feature flag is enabled

You can check if a feature flag is enabled in three different ways:

#### 1. Using the facade

```php
use Evanperreau\LaravelFeatureFlagsLite\Facades\Feature;

if (Feature::isEnabled('new_ui')) {
    // The 'new_ui' feature flag is enabled
}
```

#### 2. Using the helper function

```php
if (feature('new_ui')) {
    // The 'new_ui' feature flag is enabled
}
```

#### 3. Using dependency injection

```php
use Evanperreau\LaravelFeatureFlagsLite\FeatureFlags;

class MyController
{
    public function index(FeatureFlags $featureFlags)
    {
        if ($featureFlags->isEnabled('new_ui')) {
            // The 'new_ui' feature flag is enabled
        }
    }
}
```

### Using in Blade views

You can easily use feature flags in your Blade views:

```blade
@if(feature('new_ui'))
    <div class="new-ui-component">
        <!-- Content visible only if the 'new_ui' feature flag is enabled -->
    </div>
@else
    <div class="old-ui-component">
        <!-- Content visible if the 'new_ui' feature flag is disabled -->
    </div>
@endif
```

## 📝 Best Practices

### Naming feature flags

Use descriptive names for your feature flags. For example:

- `new_user_onboarding`
- `premium_dashboard`
- `beta_reporting_tools`

### Environment management

You can use different values for your feature flags depending on the environment by using environment variables in your `.env` file:

```
FEATURE_NEW_UI=true
FEATURE_BETA_FEATURE=false
```

And in your `features.php` configuration file:

```php
<?php

return [
    'new_ui' => env('FEATURE_NEW_UI', false),
    'beta_feature' => env('FEATURE_BETA_FEATURE', false),
];
```

## 👥 Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request.

## 📄 License

This package is open-source and available under the [MIT license](LICENSE.md).
