<?php

namespace App\Http\Services;

use App\Jobs\HandleUserWeatherJob;
use App\Models\User;
use Exception;

class ViewUserWeatherService
{
    public User $user;
    public int $userId;
    public array $weather;

    public function __construct($userId){
        $this->userId = $userId;
    }

    public function handle(): array
    {
        try {
            $this->getUser();
            $this->handleWeatherDataDoesNotExist();
            $this->getWeather();
            return [
                'success' => true,
                'code' => 200,
                'message' => 'User weather',
                'data' => $this->weather
            ];
        } catch (Exception $exception) {
            return [
                'code' => $exception->getCode(),
                'success' => $exception->getCode() == 201,
                'message' => $exception->getMessage()
            ];
        }
    }

    private function getUser(): void
    {
        $this->user = User::find($this->userId);
    }

    /**
     * @throws Exception
     */
    private function handleWeatherDataDoesNotExist(): void
    {
        if(is_null($this->user->weather)){
            HandleUserWeatherJob::dispatch($this->user);
            throw new Exception('Fetching an updated weather report', 201);
        }
    }

    private function getWeather(): void
    {
        $this->weather = json_decode($this->user->weather->details,true);
        $this->weather['last_updated_at'] = date('d D, M Y H:i A', strtotime($this->user->weather->updated_at));
    }
}
