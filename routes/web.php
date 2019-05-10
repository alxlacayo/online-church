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

Route::get('test', 'PlayController@index'); // delete this

Route::group(['prefix' => 'w/api'], function() {

	Route::get('schedule', 'BroadcastController@getAll');

	Route::get('broadcasts/{broadcast}', 'BroadcastController@get');

	Route::get('broadcasts/{broadcast}/time_elapsed', 'BroadcastController@getTimeElapsed');

	Route::post('broadcasts/{broadcast}/comments', 'BroadcastCommentController@create');

	Route::get('sermons', 'SermonController@getAll');

	Route::get('sermons/previous', 'SermonController@getPrevious');

	Route::get('sermons/{id}', 'SermonController@get');

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
 	
	Route::get('authorize', 'AuthorizeController');

	Route::get('dashboard', 'HostDashboardController@index');

	Route::get('comments', 'HostCommentController@getAll');

	Route::post('comments', 'HostCommentController@create');
});
	
Route::group(['prefix' => 'w/api/admin', 'namespace' => 'Admin'], function() { 
	// SECURE THIS FOR ADMINS ONLY!!!!!
	Route::post('broadcast/create', 'BroadcastController@create');
});

// Define this route so we dont get 'password.reset' route 404 error.
Route::get('password/reset/{token}', 'SPAController@index')->name('password.reset');

Route::fallback('SPAController');
