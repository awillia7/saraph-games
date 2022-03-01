<?php

use Illuminate\Http\Request;

// Home and Blog Posts
Route::get('/', 'HomeController@get_home_api');
Route::get('/posts', 'HomeController@get_home_api');
Route::get('/posts/{post}', 'PostController@get_post_api');
Route::post('/posts', 'PostController@post_post_api')
    ->middleware('auth:api');

Route::get('/admin/posts', 'HomeController@get_admin_home_api')
    ->middleware('auth:api');
Route::get('/admin/posts/{post}', 'PostController@get_admin_post_api')
    ->middleware('auth:api');

// Lexicon
Route::get('/lexicon', 'LexiconController@get_home_api');
Route::get('/lexicon/cards', 'LexiconController@get_cards_api');
Route::get('/lexicon/cards/{card}', 'LexiconController@get_card_api');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
