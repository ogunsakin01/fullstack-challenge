<?php

namespace App\Http\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;

class GetUserWeatherService
{
    public User $user;
    public int $userId;
    public string $apiEndpoint;
    private array $apiResponseBodyArray;

    public function __construct($userId){
        $this->userId = $userId;
    }

    public function handle(): array
    {
        try{
            $this->getUser();
            $this->generateAPIEndpointUrl();
            $this->getWeatherFromAPI();
            $this->saveOrUpdateUserWeatherDetails();
            return [
                'code' => 200,
                'success' => true,
                'message' => 'User weather updated',
                'data' => $this->apiResponseBodyArray
            ];
        }catch(Exception $exception){
            return [
                'code' => 422,
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    private function getUser(): void
    {
        $this->user = User::find($this->userId);
    }

    private function generateAPIEndpointUrl(): void
    {
        $endpointParams = '?lat='.$this->user->latitude.'&lon='.$this->user->longitude.'&units=metric&appid='.config('app.weather_api_key');
        $this->apiEndpoint = 'https://api.openweathermap.org/data/2.5/weather'.$endpointParams;
    }

    /**
     * @throws Exception
     */
    private function getWeatherFromAPI(): void
    {
        $apiResponse = Http::get($this->apiEndpoint);
        $this->apiResponseBodyArray = json_decode($apiResponse->body(), true);
        if($apiResponse->failed()){
            throw new Exception('Unable to retrieve user weather data, error: '. $this->apiResponseBodyArray['message'], 422);
        }
    }

    private function saveOrUpdateUserWeatherDetails(): void
    {
        $this->user->weather()->updateOrCreate([
            'user_id' => $this->userId
        ], [
            'details' => json_encode($this->apiResponseBodyArray)
        ]);
    }

}
