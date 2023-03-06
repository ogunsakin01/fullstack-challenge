<?php

namespace App\Jobs;

use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleUsersWeatherDataUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array|Collection $oldWeatherData;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->oldWeatherData = Weather::query()->whereDate('created_at', '<', Carbon::now()->subHour())->get();
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
    }
}
