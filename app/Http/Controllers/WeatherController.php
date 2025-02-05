<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function show(Request $request)
    {
        $city = $request->query('city', 'Cairo'); // المدينة الافتراضية: القاهرة
        $weatherData = $this->weatherService->getWeather($city);

        return response()->json([
            'city' => $weatherData['name'],
            'temperature' => $weatherData['main']['temp'],
            'weather' => $weatherData['weather'][0]['description'],
        ]);
    }
}
