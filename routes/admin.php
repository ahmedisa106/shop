<?php


define('PAGINATION_COUNT', 10);
Route::group(['middleware' => 'auth:admin'], function () {

    Route::get('/', 'DashboardController@index')->name('admin.dashboard');

    /*languages routes*/
    Route::group(['prefix' => 'languages'], function () {

        Route::get('/', 'LanguagesController@index')->name('admin.languages');
        Route::get('/create', 'LanguagesController@create')->name('admin.languages.create');
        Route::post('store', 'LanguagesController@store')->name('admin.languages.store');
        Route::get('edit/{id}', 'LanguagesController@edit')->name('admin.languages.edit');
        Route::put('update/{id}', 'LanguagesController@update')->name('admin.languages.update');
        Route::get('delete/{id}', 'LanguagesController@destroy')->name('admin.languages.delete');

    });

    /* mainCategories routes*/
    Route::group(['prefix' => 'mainCategories'], function () {

        Route::get('/', 'MainCategoryController@index')->name('admin.mainCategories');
        Route::get('/create', 'MainCategoryController@create')->name('admin.mainCategories.create');
        Route::post('store', 'MainCategoryController@store')->name('admin.mainCategories.store');
        Route::get('edit/{id}', 'MainCategoryController@edit')->name('admin.mainCategories.edit');
        Route::put('update/{id}', 'MainCategoryController@update')->name('admin.mainCategories.update');
        Route::get('delete/{id}', 'MainCategoryController@destroy')->name('admin.mainCategories.delete');

    });

    /* Vendors routes*/
    Route::group(['prefix' => 'vendors'], function () {

        Route::get('/', 'VendorsController@index')->name('admin.vendors');
        Route::get('/create', 'VendorsController@create')->name('admin.vendors.create');
        Route::post('store', 'VendorsController@store')->name('admin.vendors.store');
        Route::get('edit/{id}', 'VendorsController@edit')->name('admin.vendors.edit');
        Route::put('update/{id}', 'VendorsController@update')->name('admin.vendors.update');
        Route::get('delete/{id}', 'VendorsController@destroy')->name('admin.vendors.delete');

    });

});


Route::group(['middleware' => 'guest:admin'], function () {

    Route::get('login', 'LoginController@getLogin')->name('get.admin.login');
    Route::post('login', 'LoginController@login')->name('admin.login');


});


