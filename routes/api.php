<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;

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

Route::get('view', function () {
    return view('welcome');
})->middleware(['auth:sanctum']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('createuser', [UserController::class, 'createUser']);
Route::delete('deleteuser', [UserController::class, 'deleteUser']);
Route::get('logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
Route::patch('updateuser', [UserController::class, 'updateUser'])->middleware(['auth:sanctum']);
Route::get('logininfo', [AuthenticationController::class, 'logininfo'])->middleware(['auth:sanctum']);