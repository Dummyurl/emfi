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
Route::get('TopGainer/{market_id}', 'ApiController@TopGainer');
Route::get('TopLoser/{market_id}', 'ApiController@TopLoser');
