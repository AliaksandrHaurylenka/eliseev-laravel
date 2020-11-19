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
      'middleware' => ['auth', 'can:admin-panel'],
  ],
  function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::resource('users', 'UsersController');
    Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');

    Route::resource('regions', 'RegionController');

    Route::group(['prefix' => 'adverts', 'as' => 'adverts.', 'namespace' => 'Adverts'], function () {

        Route::resource('categories', 'CategoryController');

        Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], function () {
            Route::post('/first', 'CategoryController@first')->name('first');
            Route::post('/up', 'CategoryController@up')->name('up');
            Route::post('/down', 'CategoryController@down')->name('down');
            Route::post('/last', 'CategoryController@last')->name('last');
            Route::resource('attributes', 'AttributeController')->except('index');
        });

//         Route::group(['prefix' => 'adverts', 'as' => 'adverts.'], function () {
//             Route::get('/', 'AdvertController@index')->name('index');
//             Route::get('/{advert}/edit', 'AdvertController@editForm')->name('edit');
//             Route::put('/{advert}/edit', 'AdvertController@edit');
//             Route::get('/{advert}/photos', 'AdvertController@photosForm')->name('photos');
//             Route::post('/{advert}/photos', 'AdvertController@photos');
//             Route::get('/{advert}/attributes', 'AdvertController@attributesForm')->name('attributes');
//             Route::post('/{advert}/attributes', 'AdvertController@attributes');
//             Route::post('/{advert}/moderate', 'AdvertController@moderate')->name('moderate');
//             Route::get('/{advert}/reject', 'AdvertController@rejectForm')->name('reject');
//             Route::post('/{advert}/reject', 'AdvertController@reject');
//             Route::delete('/{advert}/destroy', 'AdvertController@destroy')->name('destroy');
//         });
    });
  }
);

