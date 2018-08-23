<?php

Route::get('/units', 'UnitsController@index')->name('units.index');
Route::get('/units/{id}', 'UnitsController@show')->name('units.show');
Route::get('/long-term-rentals', 'LongTermRentalsController@index')->name('ltr.show');
Route::get('/beachfront', 'BeachfrontController@index')->name('beachfront.show');
Route::get('/availability', 'AvailabilityController@index')->name('availability.index');
Route::get('/amenities', 'AmenitiesController@index')->name('amenities.index');

Route::get('/search', 'UnitSearchController@index')->name('units.search');
Route::get('/matches', 'MatchingResultsController@index')->name('matches');
