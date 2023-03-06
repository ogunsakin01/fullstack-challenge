<?php

namespace Tests\Unit;

use App\Http\Services\ViewUserWeatherService;
use App\Models\User;
use Tests\TestCase;

class ViewUserWeatherServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_user_without_weather_is_viewed()
    {
        $user = User::factory()->create();
        $response = (new ViewUserWeatherService($user->id))->handle();
        $this->assertTrue($response['success']);
        $this->assertEquals(201, $response['code']);
        $this->assertEquals('Fetching an updated weather report', $response['message']);
    }


    public function test_that_user_with_weather_data_is_viewed(){
        $user = User::query()->orderBy('id', 'desc')->first();
        $response = (new ViewUserWeatherService($user->id))->handle();
        $this->assertTrue($response['success']);
        $this->assertIsArray($response['data']);
        $expectedKeys = ['coord', 'weather', 'base', 'main', 'visibility', 'wind', 'clouds', 'dt', 'sys', 'timezone', 'id', 'name', 'cod'];
        foreach ($expectedKeys as $expectedKey){
            $this->assertArrayHasKey($expectedKey, $response['data']);
        }
    }
}
