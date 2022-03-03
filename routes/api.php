<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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


/**
 * Public Routes
 * No any restrictions to use there routes
 */
Route::post('/register' , [UserController::class, 'register']);
Route::post('/log-in' , [UserController::class, 'login']);



/**
 * Protected Routes
 * Logged users can only access those routes
 */
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/log-out' , [UserController::class, 'logout']);    
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
