<?php

namespace App\Services\Providers;

use App\Constants\ApiEndpointConstants;
use App\Services\Providers\Interfaces\ProviderInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ProviderTwo implements ProviderInterface
{
    public function fetchTasks(): array
    {
        $provider2ApiResult = Http::get(config('provider.api.base_url') . ApiEndpointConstants::PROVIDER_2_URL);

        if ($provider2ApiResult->failed()) {
            return [];
        }

        return Arr::map($provider2ApiResult->json(), function ($task) {
            return [
                'provider_id' => 2,
                'name' => 'Provider 2 Task ' . $task['id'],
                'duration' => $task['sure'],
                'difficulty' => $task['zorluk'],
            ];
        });
    }
}
