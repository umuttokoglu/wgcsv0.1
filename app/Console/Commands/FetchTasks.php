<?php

namespace App\Console\Commands;

use App\Constants\ProviderNameConstants;
use App\Models\Task;
use App\Services\Providers\Factories\ProviderFactory;
use Illuminate\Console\Command;

class FetchTasks extends Command
{
    protected $signature = 'tasks:fetch';
    protected $description = 'Fetch tasks from all providers and store in the database...';

    public function handle(): int
    {
        $providers = [
            ProviderNameConstants::PROVIDER_1,
            ProviderNameConstants::PROVIDER_2
        ];

        foreach ($providers as $provider) {
            try {
                $tasks = ProviderFactory::create($provider)->fetchTasks();

                if (empty($tasks)) {
                    $this->alert(__('console.fetch_tasks.no_task_alert'));

                    return 0;
                }

                foreach ($tasks as $task) {
                    Task::updateOrCreate([
                        'provider_id' => $task['provider_id'],
                        'name' => $task['name']
                    ], [
                        'duration' => $task['duration'],
                        'difficulty' => $task['difficulty']
                    ]);
                }

                $this->info(__('console.fetch_tasks.info', ['provider' => $provider]));
            } catch (\Exception $e) {
                $this->error(__('console.fetch_tasks.error', ['provider' => $provider, 'error' => $e->getMessage()]));
            }
        }

        return 0;
    }
}
