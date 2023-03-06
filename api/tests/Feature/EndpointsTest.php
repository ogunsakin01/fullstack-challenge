<?php

namespace Tests\Feature;

use App\Http\Services\ViewUserWeatherService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_get_user_weather_returns_a_pending_response()
    {
        $user = User::factory()->create();
        $response = $this->get('/get-user-weather/'.$user->id);
        $response->assertStatus(201);
    }

    public function test_the_get_user_weather_returns_a_successful_response()
    {
        $user = User::factory()->create();
        (new ViewUserWeatherService($user->id))->handle();
        $response = $this->get('/get-user-weather/'.$user->id);
        $response->assertStatus(200);
    }

    public function test_the_get_users_returns_a_successful_response()
    {
        $response = $this->get('/users');
        $response->assertStatus(200);
    }
}
