<?php

namespace Tests\Unit;

use App\Http\Services\GetUserWeatherService;
use App\Http\Services\ViewUserWeatherService;
use App\Jobs\HandleUserWeatherJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ViewUserWeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->setupUser();
    }

    public function test_that_user_without_weather_can_be_viewed()
    {
        $response = (new ViewUserWeatherService($this->user->id))->handle();
        $this->assertTrue($response['success']);
        $this->assertEquals(201, $response['code']);
        $this->assertEquals('Fetching an updated weather report', $response['message']);
    }

    public function test_that_weather_handle_job_is_dispatched()
    {
        Queue::fake();
        (new ViewUserWeatherService($this->user->id))->handle();
        Queue::assertPushed(HandleUserWeatherJob::class);
    }


    public function test_that_user_with_weather_data_can_be_viewed(){
        (new GetUserWeatherService($this->user->id))->handle();
        $response = (new ViewUserWeatherService($this->user->id))->handle();
        $this->assertTrue($response['success']);
        $this->assertIsArray($response['data']);
        $expectedKeys = ['coord', 'weather', 'base', 'main', 'visibility', 'wind', 'clouds', 'dt', 'sys', 'timezone', 'id', 'name', 'cod'];
        foreach ($expectedKeys as $expectedKey){
            $this->assertArrayHasKey($expectedKey, $response['data']);
        }
    }

    private function setupUser(){
        $this->user = User::factory()->create();
    }
}
