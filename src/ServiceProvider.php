<?php

namespace Engine;

use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function boot() 
    {
        $this->publishes([
            __DIR__.'/../config/engine.php' => config_path('engine.php'),
        ]);

        if (config('engine.routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }
    }
}
