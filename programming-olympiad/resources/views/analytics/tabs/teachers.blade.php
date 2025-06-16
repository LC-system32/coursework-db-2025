<div class="tab-pane fade show active" id="teachers" role="tabpanel">
    {{-- Загальні показники --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center py-4">
                <i class="bi bi-person-video3 fs-1 text-primary mb-2"></i>
                <h6 class="text-muted">Кількість вчителів</h6>
                <h2 class="fw-bold text-primary">{{ $teachersCount }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center py-4">
                <i class="bi bi-people-fill fs-1 text-success mb-2"></i>
                <h6 class="text-muted">Середня кількість учнів на одного вчителя</h6>
                <h2 class="fw-bold text-success">{{ $avgParticipantsPerTeacher }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center py-4">
                <i class="bi bi-bar-chart-line-fill fs-1 text-warning mb-2"></i>
                <h6 class="text-muted">Середній бал всіх вчителів</h6>
                <h2 class="fw-bold text-warning">{{ $averageScore }}</h2>
            </div>
        </div>
    </div>

    {{-- Найкращий + ТОП-5 --}}
    <div class="row g-4 mb-4">
        {{-- Найкращий --}}
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 text-center p-5 bg-info-subtle">
                <i class="bi bi-star-fill fs-1 text-warning mb-3"></i>
                <h5 class="fw-bold mb-2">Найкращий вчитель</h5>
                <p class="h4 mb-1 text-primary">{{ $bestTeacher['full_name'] }}</p>
                <small class="text-secondary">Середній бал: <span class="fw-semibold">{{ round($bestTeacher['avg_score'],1) }}</span></small>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light fw-semibold text-uppercase text-center">
                    <i class="bi bi-trophy-fill me-2 text-warning"></i>Топ-5 вчителів за середнім балом
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($topTeachers as $t)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $t['full_name'] }}</span>
                            <strong class="text-primary">{{ $t['avg_score'] }}</strong>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Графік активності --}}
    <div class="card shadow-sm rounded-4 mb-4">
        <div class="card-header bg-light fw-semibold text-uppercase text-center">
            <i class="bi bi-graph-up-arrow me-2 text-primary"></i>Динаміка активності вчителів (кількість спроб)
        </div>
        <div class="card-body">
            <canvas id="teacherActivityChart" height="120"></canvas>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script>
    new Chart(document.getElementById('teacherActivityChart'), {
        type: 'line',
        data: {
            labels: @json(array_keys($activityOverTime -> toArray())),
            datasets: [{
                label: 'Кількість спроб',
                data: @json(array_values($activityOverTime -> toArray())),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>