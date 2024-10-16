<?php

namespace App\Services;

use App\Models\Developer;
use App\Models\Task;
use DB;

class ToDoPlannerService
{
    public function assignments(): array
    {
        $developers = Developer::query()
            ->orderBy('id')
            ->get();

        $remainingTasks = Task::query()
            ->orderByDesc(DB::raw('difficulty * duration'))
            ->get()
            ->toArray();

        $weeks = [];

        while (!empty($remainingTasks)) {
            $week = [];
            $developerWeeklyLoad = array_fill_keys($developers->pluck('id')->toArray(), 0);

            foreach ($remainingTasks as $key => $task) {
                $selectedDeveloper = null;
                $minimumWeeklyLoad = null;

                foreach ($developers as $developer) {
                    $taskWork = $task['duration'] / $developer->efficiency;

                    if (($developerWeeklyLoad[$developer->id] + $taskWork) <= $developer->weekly_hours
                        && ($minimumWeeklyLoad === null || $developerWeeklyLoad[$developer->id] < $minimumWeeklyLoad)) {
                        $minimumWeeklyLoad = $developerWeeklyLoad[$developer->id];
                        $selectedDeveloper = $developer;
                    }
                }

                if ($selectedDeveloper) {
                    $week[$selectedDeveloper->id][] = $task;
                    $developerWeeklyLoad[$selectedDeveloper->id] += $task['duration'] / $selectedDeveloper->efficiency;

                    unset($remainingTasks[$key]);
                }
            }

            $weeks[] = $week;
        }

        return [
            'schedule' => $weeks,
            'total_weeks' => count($weeks),
            'developers' => $developers->keyBy('id')
        ];
    }
}
