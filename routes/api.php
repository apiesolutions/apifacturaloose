<?php

Route::post('search', 'SearchController@store');

Route::middleware('auth:api')->group(function() {
    Route::post('documents', 'Api\DocumentController@store');
    Route::post('send', 'Api\DocumentController@send');
    Route::post('summaries/status', 'Api\SummaryController@status');
    Route::post('summaries', 'Api\SummaryController@store');
    Route::post('voided/status', 'Api\VoidedController@status');
    Route::post('voided', 'Api\VoidedController@store');
    Route::post('retentions', 'Api\RetentionController@store');
    Route::post('perceptions', 'Api\PerceptionController@store');
    Route::post('dispatches', 'Api\DispatchController@store');

    Route::get('services/ruc/{number}', 'Api\ServiceController@ruc');
    Route::get('services/dni/{number}', 'Api\ServiceController@dni');
});
Route::post('services/validate_cpe', 'Api\ServiceController@validateCpe');
Route::post('services/consult_status', 'Api\ServiceController@consultStatus');
Route::post('services/consult_cdr_status', 'Api\ServiceController@consultCdrStatus');
Route::post('documents/status', 'Api\ServiceController@documentStatus');