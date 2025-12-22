<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // <-- We are adding this line

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

// This is your existing health check route
Route::get('/health', function (Request $request) {
    return response()->json([
        'app' => config('app.name'),
        'status' => 'ok',
        'time' => now()->toDateTimeString(),
    ]);
});

// ADD THIS NEW ROUTE FOR REGISTRATION:
Route::post('/register', [AuthController::class, 'register']);