<?php

namespace App\Http\Controllers;

use App\Services\ToDoPlannerService;
use Illuminate\View\View;

class ToDoPlannerController extends Controller
{
    public function __invoke(ToDoPlannerService $toDoPlannerService): View
    {
        $scheduleData = $toDoPlannerService->assignments();

        return view('todo_planner.index', [
            'schedule' => $scheduleData['schedule'],
            'total_weeks' => $scheduleData['total_weeks'],
            'developers' => $scheduleData['developers']
        ]);
    }
}
