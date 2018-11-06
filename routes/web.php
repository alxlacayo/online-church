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

Route::group(['prefix' => 'w/api'], function() { 

	Route::get('home', 'HomeController@index');

	Route::resource('sermons', 'SermonController')->only([
	    'index', 'show'
	]);

	Route::get('schedule', 'BroadcastController@index');

	Route::get('broadcasts/{broadcast}', 'BroadcastController@show');

	Route::get('broadcasts/{broadcast}/comments', 'BroadcastCommentController@index');

	Route::post('broadcasts/{broadcast}/comments', 'BroadcastCommentController@store');

	Route::post('login', 'Auth\LoginController@login');

	Route::post('register', 'Auth\RegisterController@register');

	Route::post('logout', 'Auth\LoginController@logout');

	Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

	Route::post('password/reset', 'Auth\ResetPasswordController@reset');

	Route::get('user/me', 'UserController@me');

	Route::patch('user/edit', 'UserController@update');

	Route::patch('user/picture', 'UserController@updateProfilePicture');

	Route::delete('user/picture', 'UserController@destroyProfilePicture');

	Route::post('contact', 'ContactController@index');
});

Route::group(['prefix' => 'w/api/host', 'namespace' => 'Host'], function() { 
 	
	Route::get('dashboard', 'HostDashboardController@index');

	Route::get('comments', 'HostCommentController@index');

	Route::post('comments', 'HostCommentController@store');
});

Route::group(['prefix' => 'w/api/admin'], function() { 

	Route::put('sermons/{sermon}/edit', 'SermonController@update');
});

// Define this route so we dont get 'password.reset' route 404 error.
Route::get('password/reset/{token}', 'SPAController@index')->name('password.reset');

Route::fallback('SPAController@index');
