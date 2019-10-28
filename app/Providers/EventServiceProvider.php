<?php

namespace Weboffice\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Weboffice\Models\Transaction;
use AppConfig;
use Weboffice\Models\Invoice;
use Weboffice\Models\Quote;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Weboffice\Events\SomeEvent' => [
            'Weboffice\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

		// Handle transaction creation, by creating the
		// hash and checking for duplicate
		Transaction::creating(function($transaction)
		{
		    if( $transaction->datum && $transaction->bedrag && $transaction->rekening_id ) {
		    	// Generate a hash and see if it already exists
		    	$hash = $transaction->createHash();
		    	
		    	// Check for duplicates (discard the current transaction)
		    	$query = Transaction::where('hash', $hash);
		    	if( $transaction->id ) {
		    		$query = $query->where('id', '<>', $transaction->id);
		    	}
		    	
		    	// If a duplicate is found, cancel save
		    	if($query->count() > 0) {
		    		return false;
		    	}
		    	
		    	// Save the hash
		    	$transaction->hash = $hash;
		    	return true;
		    }
		});
        
		// Handle invoice and quote creation, storing the newly created 
		// invoice number in configuration
		Invoice::created(function($invoice) {
			AppConfig::set('factuurNummer', $invoice->factuurnummer);
		});
		
		Quote::created(function($quote) {
			AppConfig::set('offerteNummer', $quote->offertenummer);
		});
        
    }
}
