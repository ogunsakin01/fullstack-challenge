<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json([
        'message' => 'all systems are a go',
        'users' => \App\Models\User::all(),
        'old_weathers' => Weather::query()->where('updated_at', '<', Carbon::now()->subMinutes(10))->get()
    ]);
});

Route::get('/users', [UserController::class, 'index']);
Route::get('/get-user-weather/{id}', [WeatherController::class, 'getUserWeather']);
