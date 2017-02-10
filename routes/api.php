<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Topics
Route::get('/topics', 'TopicController@index');
Route::get('/topics/{id}', 'TopicController@show');
Route::post('/topics', 'TopicController@store');
Route::put('/topics/{id}', 'TopicController@update');
Route::delete('/topics/{id}', 'TopicController@destroy');

// Tags
Route::get('/tags', 'TagController@index');
Route::get('/tags/{id}', 'TagController@show');
Route::post('/tags', 'TagController@store');
Route::put('/tags/{id}', 'TagController@update');
Route::delete('/tags/{id}', 'TagController@destroy');

// Categories
Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{id}', 'CategoryController@show');
Route::post('/categories', 'CategoryController@store');
Route::put('/categories/{id}', 'CategoryController@update');
Route::delete('/categories/{id}', 'CategoryController@destroy');

// Sources
Route::get('/sources', 'SourceController@index');
Route::get('/sources/{id}', 'SourceController@show');
Route::post('/sources', 'SourceController@store');
Route::put('/sources/{id}', 'SourceController@update');
Route::delete('/sources/{id}', 'SourceController@destroy');

// Mailboxes
Route::get('/mailboxes', 'MailboxController@index');
Route::get('/mailboxes/{id}', 'MailboxController@show');
Route::post('/mailboxes', 'MailboxController@store');
Route::put('/mailboxes/{id}', 'MailboxController@update');
Route::delete('/mailboxes/{id}', 'MailboxController@destroy');

// Leave in to make Test1 work.
Route::get('/topics/count', 'TopicController@count');
