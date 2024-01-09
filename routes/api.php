<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WishlistController;
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

//login
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('createuser', [UserController::class, 'createUser']);
Route::delete('deleteuser', [UserController::class, 'deleteUser']);
Route::get('logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
Route::patch('updateuser', [UserController::class, 'updateUser'])->middleware(['auth:sanctum']);
Route::get('logininfo', [AuthenticationController::class, 'logininfo'])->middleware(['auth:sanctum']);
//refresh token


//budget
Route::post('setBudgetWants', [CategoryController::class, 'setBudgetWants'])->middleware(['auth:sanctum']);
Route::post('setBudgetNeeds', [CategoryController::class, 'setBudgetNeeds'])->middleware(['auth:sanctum']);
Route::post('setBudgetSavings', [CategoryController::class, 'setBudgetSavings'])->middleware(['auth:sanctum']);
Route::post('setBudgetSekaligus', [CategoryController::class, 'setBudgetSekaligus'])->middleware(['auth:sanctum']);
Route::get('BudgetChecker', [CategoryController::class, 'BudgetChecker'])->middleware(['auth:sanctum']);
Route::get('getUserBudgets', [UserController::class, 'getUserBudgets'])->middleware(['auth:sanctum']);

//expense
Route::post('addExpense', [ExpenseController::class, 'store'])->middleware(['auth:sanctum']);
Route::post('updateExpense', [ExpenseController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('deleteExpense', [ExpenseController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::post('displayExpense', [ExpenseController::class, 'show'])->middleware(['auth:sanctum']);
Route::get('displayExpenseWithoutDate', [ExpenseController::class, 'showWithoutDate'])->middleware(['auth:sanctum']);

//analytics
Route::post('displayCategoryTotalExpense', [ExpenseController::class, 'displayCategoryTotalExpense'])->middleware(['auth:sanctum']);
Route::get('getTotalExpenses', [ExpenseController::class, 'getTotalExpenses'])->middleware(['auth:sanctum']);
Route::get('getNeedsBudgetInsight', [ExpenseController::class, 'getNeedsBudgetInsight'])->middleware(['auth:sanctum']);
Route::get('getWantsBudgetInsight', [ExpenseController::class, 'getWantsBudgetInsight'])->middleware(['auth:sanctum']);
Route::get('getSavingsBudgetInsight', [ExpenseController::class, 'getSavingsBudgetInsight'])->middleware(['auth:sanctum']);
Route::get('getTotalExpensesPercentage1', [ExpenseController::class, 'getTotalExpensesPercentage1'])->middleware(['auth:sanctum']);
Route::get('getTotalExpensesPercentage2', [ExpenseController::class, 'getTotalExpensesPercentage2'])->middleware(['auth:sanctum']);
Route::get('getTotalExpensesPercentage3', [ExpenseController::class, 'getTotalExpensesPercentage3'])->middleware(['auth:sanctum']);


//wishlist
Route::post('addWishlist', [WishlistController::class, 'store'])->middleware(['auth:sanctum']);
Route::post('updateStatusWishlist', [WishlistController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('deleteWishlist', [WishlistController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::get('getWishlist', [WishlistController::class, 'show'])->middleware(['auth:sanctum']);


