<?php

// Home and Blog Posts
Route::get('/', 'HomeController@get_home_web');
Route::get('/posts/{post}', 'PostController@get_post_web');

// Lexicon
Route::get('/lexicon', 'LexiconController@get_home_web');
Route::get('/lexicon/cards', 'LexiconController@get_cards_web');
Route::get('/lexicon/cards/{card}', 'LexiconController@get_card_web');

Auth::routes();
