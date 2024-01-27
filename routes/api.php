<?php

use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/register',[UserController::class,'register']);

Route::post('/create-user',[UserController::class,'createUser']);

Route::post('/login',[UserController::class,'login']);

Route::post('/create-product',[UserController::class,'createProduct']);

Route::get('/view-products',[UserController::class,'viewProducts']);

Route::post('/delete-product',[UserController::class,'deleteProduct']);

Route::post('/edit-product/{id}',[UserController::class,'updateProduct']);



