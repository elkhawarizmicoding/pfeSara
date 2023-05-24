<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\AppController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('search/{query}', [AppController::class, 'search']);
});


Route::post('login', [AppController::class, 'login']);
Route::get('test/{query}', [TestController::class, 'test']);
