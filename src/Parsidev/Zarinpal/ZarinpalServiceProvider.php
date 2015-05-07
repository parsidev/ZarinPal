<?php namespace Parsidev\Zarinpal;

use Illuminate\Support\ServiceProvider;

class ZarinpalServiceProvider extends ServiceProvider {

	protected $defer = false;

    public function boot() {
        $this->publishes([
            __DIR__ . '/../../config/zarinpal.php' => config_path('zarinpal.php'),
        ]);
    }

    public function register() {
        $this->app['zarinpal'] = $this->app->share(function($app) {
            $config = config('zarinpal');
            return new Zarinpal($config, new SoapClient($config['webServiceUrl'], array('encoding' => 'UTF-8')));
        });
    }

    public function provides() {
        return ['zarinpal'];
    }

}
