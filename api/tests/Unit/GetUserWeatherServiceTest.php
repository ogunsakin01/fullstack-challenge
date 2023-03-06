<?php

namespace Tests\Unit;

use App\Http\Services\GetUserWeatherService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class GetUserWeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_user_weather_can_be_retrieved_from_api_successfully()
    {
        $user = User::factory()->create();
        $response = (new GetUserWeatherService($user->id))->handle();
        $this->assertTrue($response['success']);
        $this->assertEquals(200, $response['code']);
        $this->assertIsArray($response['data']);
        $expectedKeys = ['coord', 'weather', 'base', 'main', 'visibility', 'wind', 'clouds', 'dt', 'sys', 'timezone', 'id', 'name', 'cod'];
        foreach ($expectedKeys as $expectedKey){
            $this->assertArrayHasKey($expectedKey, $response['data']);
        }
    }

    public function test_that_user_weather_can_not_be_retrieved_from_api()
    {
        $user = User::factory()->create();
        $user->longitude = null;
        $user->latitude = null;
        $user->update();
        $response = (new GetUserWeatherService($user->id))->handle();
        $this->assertFalse($response['success']);
        $this->assertEquals(422, $response['code']);
    }
}
