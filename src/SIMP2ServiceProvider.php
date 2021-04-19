<?php

namespace SIMP2\SDK;

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
        $this->mergeConfigFrom($this->configPath(), 'simp2');
        $this->publishes([$this->configPath() => config_path('simp2.php')]);
    }

    protected function configPath(): string
    {
        return __DIR__ . '/../config/simp2.php';
    }
}
