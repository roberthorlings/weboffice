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

Route::get('/', function () {
    return view('welcome');
});

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