<?php

namespace App\Providers;

use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use OwaSdk\sdk;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(Cookie::get('tracking', 'no') === 'yes') {
            $OWA_INSTANCE_URL = env("OWA_INSTANCE_URL", false);
            $OWA_SITE_ID = env("OWA_SITE_ID", false);
            $OWA_API_KEY = env("OWA_API_KEY", false);
            $OWA_AUTH_KEY = env("OWA_AUTH_KEY", false);

            if ($OWA_INSTANCE_URL && $OWA_SITE_ID && $OWA_API_KEY && $OWA_AUTH_KEY) {
                $config = [
                    'instance_url' => $OWA_INSTANCE_URL,
                    'credentials' => [
                        'api_key' => $OWA_API_KEY,
                        'auth_key' => $OWA_AUTH_KEY
                    ]
                ];

                $sdk = new sdk($config);
                $tracker = $sdk->createTracker();
                $tracker->setSiteId($OWA_SITE_ID);
                //$tracker->setPageTitle('Standalone PHP Test Page3');

                if (!str_starts_with($_SERVER['REQUEST_URI'], '/api')) {
                    $tracker->trackPageView();
                }

                if(Auth::check()) {
                    $tracker->setUserName(Auth::user()->name);
                    $tracker->setUserEmail(Auth::user()->email);
                }
            }
        }

        if(!Response::hasMacro('flash')) {
            Response::macro('flash', function (array $array) {
                $content = '';

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

                $response = Response::make($content)->header('Content-Type', 'x-www-form-urlencoded');

                Log::info("Response:", [$response->content()]);

                return $response;
            });
        }
    }
}
