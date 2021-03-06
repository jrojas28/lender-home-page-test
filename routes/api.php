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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/teams/{id}/players', 'API\TeamController@players');
Route::get('/players/freeAgents', 'API\PlayerController@freeAgents');

Route::apiResources([
    'players' => 'API\PlayerController',
    'teams' => 'API\TeamController',
]);