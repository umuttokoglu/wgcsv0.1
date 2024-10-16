<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Developer;
use App\Models\Task;
use App\Services\ToDoPlannerService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ToDoPlannerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Developer::factory()->create(['name' => 'DEV1', 'efficiency' => 1]);
        Developer::factory()->create(['name' => 'DEV2', 'efficiency' => 2]);
        Developer::factory()->create(['name' => 'DEV3', 'efficiency' => 3]);
        Developer::factory()->create(['name' => 'DEV4', 'efficiency' => 4]);
        Developer::factory()->create(['name' => 'DEV5', 'efficiency' => 5]);
    }

    /** @test */
    public function it_schedules_tasks_in_a_single_week_if_possible()
    {
        Task::factory()->create(['name' => 'Task 1', 'duration' => 5, 'difficulty' => 3]);
        Task::factory()->create(['name' => 'Task 2', 'duration' => 4, 'difficulty' => 2]);

        $toDoPlannerService = new ToDoPlannerService();
        $scheduleData = $toDoPlannerService->assignments();

        $this->assertEquals(1, $scheduleData['total_weeks']);

        $this->assertCount(1, $scheduleData['schedule']);
    }

    /** @test */
    public function it_spreads_tasks_across_multiple_weeks_if_needed()
    {
        for ($i = 1; $i <= 40; $i++) {
            Task::factory()->create([
                'name' => "Task $i",
                'duration' => 10,
                'difficulty' => 15
            ]);
        }

        $toDoPlannerService = new ToDoPlannerService();
        $scheduleData = $toDoPlannerService->assignments();

        $this->assertGreaterThanOrEqual(2, $scheduleData['total_weeks']);

        $this->assertEquals(2, $scheduleData['total_weeks']);

        foreach ($scheduleData['schedule'] as $week) {
            foreach ($week as $devId => $tasks) {
                $developer = Developer::find($devId);
                $totalWork = 0;

                foreach ($tasks as $task) {
                    $totalWork += $task['duration'] / $developer->efficiency;
                }

                $this->assertLessThanOrEqual($developer->weekly_hours, $totalWork);
            }
        }
    }

    /** @test */
    public function it_uses_all_developers_even_if_not_needed()
    {
        Task::factory()->create(['name' => 'Task 1', 'duration' => 2, 'difficulty' => 1]);
        Task::factory()->create(['name' => 'Task 2', 'duration' => 3, 'difficulty' => 2]);

        $toDoPlannerService = new ToDoPlannerService();
        $scheduleData = $toDoPlannerService->assignments();

        $this->assertEquals(1, $scheduleData['total_weeks']);

        $week1 = $scheduleData['schedule'][0];

        $this->assertGreaterThanOrEqual(2, count($week1));
    }

    /** @test */
    public function it_handles_no_tasks_gracefully()
    {
        $toDoPlannerService = new ToDoPlannerService();
        $scheduleData = $toDoPlannerService->assignments();

        $this->assertEquals(0, $scheduleData['total_weeks']);
        $this->assertEmpty($scheduleData['schedule']);
    }

    /** @test */
    public function it_creates_new_weeks_if_tasks_exceed_developer_capacity()
    {
        Task::factory()->create(['name' => 'Task 1', 'duration' => 100, 'difficulty' => 25]);

        $toDoPlannerService = new ToDoPlannerService();
        $scheduleData = $toDoPlannerService->assignments();

        $this->assertGreaterThanOrEqual(2, $scheduleData['total_weeks']);
    }

    /** @test */
    public function it_does_not_assign_tasks_beyond_developer_capacity()
    {
        Task::factory()->create(['name' => 'Task 1', 'duration' => 30, 'difficulty' => 3]);
        Task::factory()->create(['name' => 'Task 2', 'duration' => 20, 'difficulty' => 4]);

        $toDoPlannerService = new ToDoPlannerService();
        $scheduleData = $toDoPlannerService->assignments();

        $this->assertEquals(1, $scheduleData['total_weeks']);

        $week1 = $scheduleData['schedule'][0];

        $this->assertEquals(30 / 1, array_reduce($week1[1], function ($carry, $task) {
            return $carry + ($task['duration'] / 1);
        }, 0));

        $this->assertEquals(10, array_reduce($week1[2], function ($carry, $task) {
            return $carry + ($task['duration'] / 2);
        }, 0));
    }
}
