<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HandleUsersWeatherDataUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array|Collection $oldWeatherData;
    private array|Collection $usersWithoutWeather;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->oldWeatherData = Weather::query()->where('updated_at', '<', Carbon::now()->subMinutes(5))->get();
        $this->usersWithoutWeather = User::query()->doesntHave('weather')->get();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->oldWeatherData as $oldWeatherDatum){
            $user = $oldWeatherDatum->user;
            HandleUserWeatherJob::dispatch($user);
        }

        foreach($this->usersWithoutWeather as $user){
            HandleUserWeatherJob::dispatch($user);
        }
    }
}
