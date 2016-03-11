<?php

namespace Weboffice\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use Blade;

class TemplateServiceProvider extends ServiceProvider
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
    	
    	// Register directive to show the amount
    	Blade::directive('amount', function($expression) {
    		return "&euro; <?php echo number_format((" . $expression . "), 2, ',', '.'); ?>";
    	});

   		// Register directive to show a post description
   		Blade::directive('post', function($expression) {
   			return "<span class='post'><span class='post-number'><?php echo with({$expression})->nummer; ?></span> <span class='post-description'><?php echo with({$expression})->omschrijving; ?></span></span>";
   		});
    			 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
