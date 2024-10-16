<?php

namespace App\Services\Providers;

use App\Constants\ApiEndpointConstants;
use App\Services\Providers\Interfaces\ProviderInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ProviderOne implements ProviderInterface
{
    public function fetchTasks(): array
    {
        $provider1ApiResult = Http::get(config('provider.api.base_url') . ApiEndpointConstants::PROVIDER_1_URL);

        if ($provider1ApiResult->failed()) {
            return [];
        }

        return Arr::map($provider1ApiResult->json(), function ($task) {
            return [
                'provider_id' => 1,
                'name' => 'Provider 1 Task ' . $task['id'],
                'duration' => $task['estimated_duration'],
                'difficulty' => $task['value'],
            ];
        });
    }
}
