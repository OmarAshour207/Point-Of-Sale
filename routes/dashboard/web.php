<?php

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ],
    function()
    {
        Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function(){

            Route::get('/', 'DashboardController@index')->name('index');

            Route::resource('users', 'UserController')->except(['show']);

            Route::resource('categories', 'CategoryController')->except(['show']);

            Route::resource('products', 'ProductController');

            Route::resource('clients', 'ClientController');

            Route::resource('clients.orders', 'Client\OrderController');

            Route::resource('orders', 'OrderController');

            Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');
        });
    });
