<?php

namespace App\Http\Controllers;

use App\Http\Services\ViewUserWeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function getUserWeather($id): JsonResponse
    {
        $response = (new ViewUserWeatherService($id))->handle();
        return response()->json($response, $response['code']);
    }

}
