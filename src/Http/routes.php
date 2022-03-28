<?php
use Illuminate\Support\Facades\Route;

// API -----------------------------------------------------------------------------------------------------------------
Route::group([
    'namespace' => 'PipIWYG\SablCore\Http\Controllers\Api'
], function() {
    /***************************************************************************************************************
     * Middleware: auth:api - API Routes
     **************************************************************************************************************/
    Route::group([
        'prefix' => 'api/v1',
    ], function() {
        // Generic route for all API Requests to a dynamic query parameter
        Route::match([
            'GET',
            'POST',
            'PUT',
            'DELETE',
            'PATCH'
        ], '{query?}/{action?}/{id?}', [
            'as' => 'api.sabl.query',
            'uses' => 'SablApiController@handleApiQuery',
        ]);
    });
});
