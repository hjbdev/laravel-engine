<?php

namespace Engine;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function boot()
    {
        $this->registerRouteMacro();
    }

    protected function registerRouteMacro(): void
    {
        Route::macro('engine', function () {
            Route::post('/engine', function (Request $request) {
                $request->validate([
                    '_model' => 'string|required',
                ]);

                $model = $request->get('_model');

                if ($resolvedModel = Relation::getMorphedModel($model)) {
                    $model = $resolvedModel;
                } elseif (! class_exists($model)) {
                    throw new \Exception('The model provided is invalid');
                }

                return Engine::request($model, $request);
            });
        });
    }
}
