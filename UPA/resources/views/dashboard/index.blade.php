    @extends('layouts.app')

    @section('content')

        {{-- Judul Dashboard --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
            <h4 class="fw-bold">Dashboard</h4>
        </div>

        {{-- Welcome Banner --}}
        @include('components.welcome-banner', [
        'name' => Auth::user()->name,
        'role' => Auth::user()->role,
        'progress' => 85
    ])


        {{-- Cards --}}
        <div class="row g-4 mb-4">
        @php
        $cards = [
            ['title' => 'Total TOEIC Registrations', 'amount' => $totalRegistrations, 'icon' => 'bi-person-lines-fill', 'color' => 'bg-primary', 'trend' => '', 'trendClass' => 'text-success'],




            ['title' => 'Majors', 'amount' => $majors, 'icon' => 'bi-mortarboard', 'color' => 'bg-secondary', 'trend' => '', 'trendClass' => 'text-success'],
            ['title' => 'Study Program', 'amount' => $studyPrograms, 'icon' => 'bi-building', 'color' => 'bg-danger', 'trend' => '', 'trendClass' => 'text-success'],
        ];
    @endphp


            @foreach ($cards as $card)
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-4 rounded-4">
                    <div class="d-flex gap-3 align-items-center">
                        <div class="rounded-circle {{ $card['color'] }} d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi {{ $card['icon'] }} text-white fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">{{ $card['title'] }}</div>
                            <div class="h4 mb-1">{{ $card['amount'] }}</div>
                            <div class="{{ $card['trendClass'] }} small">{{ $card['trend'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Table --}}

    @endsection

    @section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('overviewChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [{
                    label: 'Registrations',
                    data: @json($monthlyCounts),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false }},
                scales: { y: { beginAtZero: true }}
            }
        });
    </script>
    @endsection
