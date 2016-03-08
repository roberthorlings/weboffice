<?php

namespace Weboffice\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
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
		// Register a selection component for a project and relation
    	Form::component('relationProjectSelect', 'components.selectRelationProject', ['name', 'relations', 'value', 'attributes']);
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
