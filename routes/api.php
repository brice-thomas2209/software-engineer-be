<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:user-api', 'scopes:user-access']], function () {
    Route::post('property', 'PropertyController@store')
        ->name('post.property'); // tested and documented
    
    Route::post('property/{property}/analytic', 'PropertyAnalyticController@store')
        ->name('post.property.analytic'); // documented and tested

    Route::patch('property/{property}/analytic/{analyticType}', 'PropertyAnalyticController@update')
        ->name('patch.property.analytic'); // documented and tested

    Route::get('property/{property}/analytics', 'PropertyAnalyticController@get')
        ->name('get.property.analytic'); // documented and tested
       
        
    Route::get('property/analytics/summary', 'PropertyAnalyticsSummaryController@index')
        ->name('get.property.analytic.summary'); // documented and tested    
});