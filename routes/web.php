<?php

Route::resource('/', 'MunicipalityController');

Route::get('/search', [
    'as'   => 'search',
    'uses' => 'MunicipalityController@search'
]);

Route::get('/{id}', [
    'as' => 'show',
    'uses' => 'MunicipalityController@show'
]);


Route::get('/clearcache', function() {
    $exitCode = Artisan::call('cache:clear');
   return 'ok';
});