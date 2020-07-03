<?php

use Illuminate\Support\Facades\Route;

/*----------------------------------------------------------------
| Home page
|---------------------------------------------------------------*/
Route::get('/', function () {
    return view('welcome');
});


/*----------------------------------------------------------------
| Authentication
|---------------------------------------------------------------*/
Auth::routes();

/*----------------------------------------------------------------
| Admin controller
|---------------------------------------------------------------*/
Route::get('/admin', 'AdminController@index')->name('home');
Route::get('/admin/edit_menu', 'AdminController@showMenu');
Route::post('/admin/edit_menu', 'AdminController@updateMenu');
Route::get('/admin/all_clients', 'AdminController@showAllClients');
Route::get('/admin/edit_client', 'AdminController@showUpdateClient');
Route::post('/admin/edit_client', 'AdminController@updateClient');
Route::get('/admin/add_client', 'AdminController@showAddClient');
Route::post('/admin/add_client', 'AdminController@addClient');
Route::get('/admin/delete_client', 'AdminController@deleteClient');
Route::get('/admin/welcome', 'AdminController@welcome');

/*----------------------------------------------------------------
| Watsapp bot controller
|---------------------------------------------------------------*/
Route::get('/webhook', 'WBotController@webhook');