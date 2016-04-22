<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	// Home page
	Route::get('/', 'DashboardController@index');
	
	// Show the self relation (optionally as JSON)
	Route::get('relation/self', 'RelationController@getSelf');

	// Update the chosen date range
	Route::post('/daterange', ['uses' => 'DashboardController@dateRange', 'as' => 'daterange']);
	
	// Assign transactions to statements
	Route::get('transaction/{id}/assign/invoice', 'TransactionController@invoice');
	Route::post('transaction/{id}/store_invoice', 'TransactionController@store_invoice');
	Route::get('transaction/{id}/assign/transfer', 'TransactionController@transfer');
	Route::post('transaction/{id}/store_transfer', 'TransactionController@store_transfer');
	Route::get('transaction/{id}/assign/private', 'TransactionController@private_transfer');
	Route::post('transaction/{id}/store_private', 'TransactionController@store_private');
	
	// Transaction with VAT is a prefilled edit form
	// Transaction without VAT or other is just the edit form.
	Route::get('transaction/{id}/assign/costs_with_vat', 'TransactionController@costs_with_vat');
	Route::get('transaction/{id}/assign/costs_without_vat', 'TransactionController@edit');
	Route::get('transaction/{id}/assign', 'TransactionController@edit');
	
	/* Delete the statement belonging to a transaction */
	Route::delete('transaction/{id}/statement', 'TransactionController@deleteStatement');
	
	// Specific statements
	Route::get('statement/create/incoming-invoice', [ 'as' => 'statement.incoming-invoice', 'uses' => 'StatementController@incomingInvoice' ]);
	Route::post('statement/create/incoming-invoice', [ 'as' => 'statement.book-incoming-invoice', 'uses' => 'StatementController@bookIncomingInvoice' ]);
	Route::get('statement/create/cost-declaration', [ 'as' => 'statement.cost-declaration', 'uses' => 'StatementController@costDeclaration' ]);
	Route::post('statement/create/cost-declaration', [ 'as' => 'statement.book-cost-declaration', 'uses' => 'StatementController@bookCostDeclaration' ]);
	
	// Asset amortization
	Route::get('asset/{id}/statements', 'AssetController@statements');
	Route::post('asset/{id}/statements', 'AssetController@bookStatements');
	
	// Financial overviews
	Route::get('balance', 'BalanceController@index');
	Route::get('results', 'ResultsController@index');
	Route::get('ledger',  [ 'as' => 'ledger.index', 'uses' => 'LedgerController@index']);
	Route::get('vat', [ 'as' => 'vat.index', 'uses' => 'VATController@index']);
	Route::post('vat', [ 'as' => 'vat.book', 'uses' => 'VATController@book']);
	
	// Importing transactions
	Route::get('transaction/import', 'TransactionController@import');
	Route::post('transaction/import', 'TransactionController@storeImport');

	// Exporting data
	Route::get('export', ['as' => 'export.index', 'uses' => 'ExportController@index']);
	Route::get('export/year/{year?}', ['as' => 'export.year', 'uses' => 'ExportController@year']);
	Route::post('export/pdf', ['as' => 'export.pdf', 'uses' => 'ExportController@export']);
	
	// Handle documents
	Route::get('invoice/create/project', 'InvoiceController@createProjectInvoice');
	Route::get('invoice/create/creditnote', 'InvoiceController@createCreditnote');
	Route::get('invoice/{id}/pdf', [ 'as' => 'invoice.pdf', 'uses' => 'InvoiceController@pdf' ]);
	Route::post('invoice/{id}/mark_as_final', 'InvoiceController@markAsFinal');
	Route::post('invoice/{id}/statement', [ 'as' => 'invoice.statement', 'uses' => 'InvoiceController@bookStatement' ]);

	Route::get('quote/{id}/pdf', [ 'as' => 'quote.pdf', 'uses' => 'QuoteController@pdf' ]);
	Route::post('quote/{id}/mark_as_final', [ 'as' => 'quote.mark_as_final', 'uses' => 'QuoteController@markAsFinal']);
	
	// Store configuration
	Route::post('configuration/saveConfiguration', [ 'as' => 'configuration.saveConfiguration', 'uses' => 'ConfigurationController@saveAll']);
	
	// CRUD controllers
	Route::resource('workinghours', 'WorkingHoursController');
	Route::resource('posttype', 'PostTypeController');
	Route::resource('post', 'PostController');
	Route::resource('account', 'AccountController');
	Route::resource('transaction', 'TransactionController');
	Route::resource('relation', 'RelationController');
	Route::resource('project', 'ProjectController');
	Route::resource('saldo', 'SaldoController');
	Route::resource('quote', 'QuoteController');
	Route::resource('travelexpense', 'TravelExpenseController');
	Route::resource('invoice', 'InvoiceController');
	Route::resource('configuration', 'ConfigurationController');
	Route::resource('statement', 'StatementController');
	Route::resource('asset', 'AssetController');
	
});