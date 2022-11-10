<?php

namespace App\Providers;

use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Log;
use ReflectionClass;

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
        if(!Response::hasMacro('flash')) {
            Response::macro('flash', function (array $array) {
                $content = '';

                //Log::info(print_r($array, true));

                $i = 0;
                while(isset($array[$i])) {
                    $enum = $array[$i];
                    if(!($enum instanceof Enum)) continue;
                    $content .= trim(chr(38 * (strlen($content) != 0))) . urlencode((new ReflectionClass($enum))->getShortName()) . '=' . urlencode($enum->value);

                    unset($array[$i]);
                    $i++;
                }

                foreach($array as $key => $value) {
                    $content .= trim(chr(38 * (strlen($content) != 0))) . urlencode($key) . '=' . urlencode($value);
                }

                return Response::make($content)->header('Content-Type', 'x-www-form-urlencoded');
            });
        }
    }
}
