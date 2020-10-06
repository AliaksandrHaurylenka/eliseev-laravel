<?php

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');


Route::group(
  [
      'prefix' => 'admin',
      'as' => 'admin.',//для route, т.е в виде будет писаться route('admin.name')
      'namespace' => 'Admin',
      'middleware' => ['auth'],
  ],
  function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('users', 'UsersController');
  }
);

