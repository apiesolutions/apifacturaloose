<?php

    Auth::routes();

    Route::get('search', 'SearchController@index')->name('search.index');
    Route::get('buscar', 'SearchController@index')->name('search.index');
    Route::get('search/tables', 'SearchController@tables');

    Route::get('downloads/voided/{type}/{external_id}', 'VoidedController@downloadExternal')->name('voided.download_external');
    Route::get('downloads/{model}/{type}/{external_id}', 'DownloadController@downloadExternal')->name('download.external_id');

    Route::middleware('auth')->group(function() {

        Route::get('/', function () {
            if(auth()->user()->role === 'user') {
                return redirect()->route('documents.index');
            } else {
                return redirect()->route('users.index');
            }
        });
        Route::get('dashboard', 'HomeController@index')->name('dashboard');

        //Company
        Route::get('companies/create', 'CompanyController@create')->name('companies.create');
        Route::get('companies/tables', 'CompanyController@tables');
        Route::get('companies/record', 'CompanyController@record');
        Route::post('companies', 'CompanyController@store');
        Route::post('companies/uploads', 'CompanyController@uploadFile');

        //Certificates
        Route::get('certificates/record', 'CertificateController@record');
        Route::post('certificates/uploads', 'CertificateController@uploadFile');
        Route::delete('certificates', 'CertificateController@destroy');

        //Options
        Route::post('options/delete_documents', 'OptionController@deleteDocuments');

        //Users
        Route::group(['middleware' => 'admin'], function () {
            Route::get('users', 'UserController@index')->name('users.index');
            Route::get('users/columns', 'UserController@columns');
            Route::get('users/tables', 'UserController@tables');
            Route::get('users/record/{user}', 'UserController@record');
            Route::post('users', 'UserController@store');
            Route::get('users/records', 'UserController@records');
            Route::delete('users/{user}', 'UserController@destroy');
        });

        //Documents
        Route::get('documents', 'DocumentController@index')->name('documents.index');
        Route::get('documents/columns', 'DocumentController@columns');
        Route::get('documents/records', 'DocumentController@records');

        //Retentions
        Route::get('retentions', 'RetentionController@index')->name('retentions.index');
        Route::get('retentions/columns', 'RetentionController@columns');
        Route::get('retentions/records', 'RetentionController@records');

        //Perceptions
        Route::get('perceptions', 'PerceptionController@index')->name('perceptions.index');
        Route::get('perceptions/columns', 'PerceptionController@columns');
        Route::get('perceptions/records', 'PerceptionController@records');

        //Dispatches
        Route::get('dispatches', 'DispatchController@index')->name('dispatches.index');
        Route::get('dispatches/columns', 'DispatchController@columns');
        Route::get('dispatches/records', 'DispatchController@records');

        Route::get('services/ruc/{number}', 'Api\ServiceController@ruc');
        Route::get('services/dni/{number}', 'Api\ServiceController@dni');

        //Summaries
        Route::get('summaries', 'SummaryController@index')->name('summaries.index');
        Route::get('summaries/records', 'SummaryController@records');
        Route::post('summaries/documents', 'SummaryController@documents');
        Route::post('summaries', 'SummaryController@store');
        Route::get('summaries/status/{summary}', 'SummaryController@status');
        Route::get('summaries/columns', 'SummaryController@columns');
        Route::delete('summaries/{summary}', 'SummaryController@destroy');
    });
