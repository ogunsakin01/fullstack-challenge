<?php

namespace App\Jobs;

use App\Events\UserWeatherUpdate;
use App\Http\Services\GetUserWeatherService;
use App\Models\User;
use App\Models\Weather;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleUserWeatherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = (new GetUserWeatherService($this->user->id))->handle();
        if($response['code'] == 200){
            $weatherInfo = $response['data'];
            broadcast(new UserWeatherUpdate($this->user, $weatherInfo))->toOthers();
        }

    }
}
