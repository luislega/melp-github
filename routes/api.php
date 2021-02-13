<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RestaurantController;
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
    Route::get('/statistics',[RestaurantController::class,'getRestaurantsWithinRadius']);
    /*INDEX*/
    Route::get('/',[RestaurantController::class,'index']);
    /*IMPORT*/
    Route::get('/import',[RestaurantsImportController::class,'import']);
    /*CREATE*/
    Route::post('/', [RestaurantController::class,'storeRestaurant']);
    /*READ*/
    Route::get('/{restaurant}', [RestaurantController::class,'readRestaurant']);
    /*UPDATE*/
    Route::put('/', [RestaurantController::class,'editRestaurant']);
    /*DELETE*/
    Route::delete('/{restaurant_id}',[RestaurantController::class, 'deleteRestaurant']);
});


