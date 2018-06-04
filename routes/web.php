<?php

Route::get('/units', 'UnitsController@index')->name('units.index');
Route::get('/units/{unit}', 'UnitsController@show')->name('units.show');
Route::get('/long-term-rentals', 'LongTermRentalsController@index')->name('ltr.show');
Route::get('/beachfront', 'BeachfrontController@index')->name('beachfront.show');

Route::get('/search', 'UnitSearchController@index')->name('units.search');