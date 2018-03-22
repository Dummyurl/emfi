<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // for excel sheet dumping in db..
        Validator::extend('excel', function($attribute, $value, $parameters, $validator){
          if (!empty($value->getClientOriginalExtension()) && $value->getClientOriginalExtension() == 'csv' ) {
            return true;
          }
          else {
            return false;
          }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
