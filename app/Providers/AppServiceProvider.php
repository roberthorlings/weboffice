<?php

namespace Weboffice\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use Weboffice\Repositories\ConfigurationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		App::singleton('appConfig', function ($app) {
		    return new ConfigurationRepository();
		});
    }
}
