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
	Route::resource('invoice', 'InvoiceController');
	Route::resource('configuration', 'ConfigurationController');
	Route::resource('statement', 'StatementController');
	Route::resource('asset', 'AssetController');
});