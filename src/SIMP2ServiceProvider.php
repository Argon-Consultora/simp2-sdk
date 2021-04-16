<?php

namespace SIMP2\SDK;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class SIMP2ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'cors');
    }

    /**
     * Register the config for publishing
     *
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$this->configPath() => config_path('simp2.php')], 'simp2');
        }
    }

    protected function configPath(): string
    {
        return __DIR__ . '/../config/simp2.php';
    }
}
