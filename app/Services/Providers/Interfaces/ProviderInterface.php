<?php

namespace App\Services\Providers\Interfaces;

interface ProviderInterface
{
    public function fetchTasks(): array;
}
