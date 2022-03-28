<?php
use Illuminate\Support\Facades\Route;

// API ---------------------------------------------------------------------------------------------------------------------------------------------------------
Route::group([
    'namespace' => 'PipIWYG\SablCore\Http\Controllers\Api'
], function() {
    // API Route Prefix
    Route::group([
        'prefix' => 'api',
    ], function() {
        // API version Route Prefix to enable API versioning for future API version enhancements
        Route::group([
            'prefix' => 'v1',
        ], function() {
            // Generic route for all API Requests to a dynamic query parameter
            Route::match([
                'PUT',
                'POST',
                'GET',
                //'DELETE', <-- Not Implemented
                //'PATCH', <-- Not Implemented
            ], '{query?}/{id?}', [
                'as' => 'api.sabl.query',
                'uses' => 'SablApiController@handleApiQuery',
            ]);
        });
    });
});
