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
        Route::macro('engine', function () {
            Route::post('/engine', function (Request $request) {
                $request->validate([
                    '_model' => 'string|required'
                ]);

                $model = $request->get('_model');


                if ($resolvedModel = Relation::getMorphedModel($model)) {
                    $model = $resolvedModel;
                } else if(class_exists($model)) {
                    // 
                } else {
                    throw new \Exception('The model provided is invalid');
                }
            
                return Engine::request($model, $request);
            });
        });
    }
}
