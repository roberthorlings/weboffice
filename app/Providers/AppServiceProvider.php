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
    	// Set FPDF font path
    	define( 'FPDF_FONTPATH', env('FPDF_FONTPATH', base_path('resources/fonts')));
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
