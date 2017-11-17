<?php

namespace Parsidev\Zarinpal;

use Illuminate\Support\ServiceProvider;

class ZarinpalServiceProvider extends ServiceProvider {

    protected $defer = false;

    public function boot() {
        $this->publishes([
            __DIR__ . '/../../config/zarinpal.php' => config_path('zarinpal.php'),
        ]);
    }

    public function register() {
        $this->app->singleton('zarinpal', function($app) {
            $config = config('zarinpal');
            return new Zarinpal($config);
        });
    }

    public function provides() {
        return ['zarinpal'];
    }

}
