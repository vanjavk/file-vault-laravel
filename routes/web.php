<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
	if(Auth::guest()){
    	return view('welcome');
	}
	else
	{
    	return view('home');
	}
});
*/
Route::get('/', 'WelcomeController@index')->name('welcome');

Auth::routes();

Route::get('/home', 'WelcomeController@index')->name('home');


Route::get('/uploadfile','uploadController@index');
Route::post('/uploadfile','uploadController@showUploadFile');

Route::get('/editfileaccess','EditFileAccessController@index');
Route::post('/editfileaccess','EditFileAccessController@editfile');
Route::get('/removefile','EditFileAccessController@index');
Route::post('/removefile','EditFileAccessController@removefile');

Route::get('/download/{downloadid}','EditFileAccessController@download');
Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');