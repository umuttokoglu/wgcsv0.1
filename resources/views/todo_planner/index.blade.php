<!DOCTYPE html>
<head>
    <title>Görev Planlama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Haftalık Görev Planlama</h1>
    <p>Toplam Haftalar: {{ $total_weeks }}</p>

    @foreach($schedule as $weekNumber => $week)
        <div class="card mb-4">
            <div class="card-header">
                Hafta {{ $weekNumber + 1 }}
            </div>
            <div class="card-body">
                @foreach($week as $developerId => $tasks)
                    <h5>Geliştirici: {{ $developers[$developerId]->name }}</h5>
                    <ul>
                        @foreach($tasks as $task)
                            <li>{{ $task['name'] }} - Süre: {{ $task['duration'] }} saat -
                                Zorluk: {{ $task['difficulty'] }}</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
</body>
