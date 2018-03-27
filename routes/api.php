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

Route::get('SelectMarkets', 'ApiController@SelectMarkets');
Route::get('TopMarketData', 'ApiController@TopMarketData');
Route::post('market/get-market-data/history', 'ApiController@HistoryChart');
Route::post('market/get-market-data/{market_id}', 'ApiController@TopGainer');
// Route::post('economics/get-market-data/history/{country}', 'ApiController@getEconomicsHistoryChart');
Route::post('economics/get-historical-bond-data/{country}', 'ApiController@getEconomicsHistoryChart');
Route::get('get-last-date', 'ApiController@getLastUploadDate');

