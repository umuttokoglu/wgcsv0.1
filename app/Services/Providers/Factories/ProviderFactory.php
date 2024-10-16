<?php

namespace App\Services\Providers\Factories;

use App\Constants\ProviderNameConstants;
use App\Exceptions\ProviderNotFoundException;
use App\Services\Providers\Interfaces\ProviderInterface;
use App\Services\Providers\ProviderOne;
use App\Services\Providers\ProviderTwo;
use Exception;

class ProviderFactory
{
    /**
     * @throws Exception
     */
    public static function create(string $providerName): ProviderInterface
    {
        return match ($providerName) {
            ProviderNameConstants::PROVIDER_1 => new ProviderOne(),
            ProviderNameConstants::PROVIDER_2 => new ProviderTwo(),
            default => throw new ProviderNotFoundException(__('provider_factory.provider_not_found')),
        };
    }
}
