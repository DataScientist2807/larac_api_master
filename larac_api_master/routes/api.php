<?php

use App\Http\Controllers\API\AuthController;
use App\Models\Ticket;
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

// http://localhost:8000/api/users/{id}/delete

/* Route::get('/', function() {
    return response()->json([
        'message' => 'Hello, API'
    ], 200);
}); */

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
/* Route::get('/tickets', function() {
    return Ticket::all();
}); */



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
