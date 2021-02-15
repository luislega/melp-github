<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RestaurantAPIController;
use \App\Http\Controllers\RestaurantsImportController;

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

/*SWAGGER*/
/*Route::prefix('v1/admin')->group(function () {
    Route::apiResource('/projects', [ProjectsApiController::class]);
});*/

Route::prefix('restaurants')->group(function(){
    Route::get('/statistics',[RestaurantAPIController::class,'getRestaurantsWithinRadius']);
    /*INDEX*/
    Route::get('/',[RestaurantAPIController::class,'index']);
    /*IMPORT*/
    Route::get('/import',[RestaurantsImportController::class,'import']);
    /*CREATE*/
    Route::post('/', [RestaurantAPIController::class,'storeRestaurant']);
    /*READ*/
    Route::get('/{restaurant}', [RestaurantAPIController::class,'readRestaurant']);
    /*UPDATE*/
    Route::put('/', [RestaurantAPIController::class,'editRestaurant']);
    /*DELETE*/
    Route::delete('/{restaurant_id}',[RestaurantAPIController::class, 'deleteRestaurant']);
});


