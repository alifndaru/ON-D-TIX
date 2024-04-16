<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */


    public function boot()
    {

        Validator::extend('date_not_past', function ($attribute, $value, $parameters, $validator) {
            $today = Carbon::today();
            $givenDate = Carbon::parse($value);

            if ($givenDate->equalTo($today)) {
                $time = $validator->getData()['jam'];
                $givenTime = Carbon::createFromFormat('H:i', $time);
                if ($givenTime->gt(Carbon::now())) {
                    return true;
                } else {
                    return false;
                }
            }
            return $givenDate->gt($today);
        });
    }
}
