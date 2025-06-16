<div class="tab-content" id="analyticsTabsContent">
    <div class="tab-pane fade show active" id="general" role="tabpanel">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center p-3">
                    <i class="bi bi-people-fill fs-3 text-primary mb-2"></i>
                    <h6 class="text-muted">Учасники</h6>
                    <h3 class="fw-bold text-primary">{{ $participantsCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center p-3">
                    <i class="bi bi-check2-square fs-3 text-success mb-2"></i>
                    <h6 class="text-muted">Спроби</h6>
                    <h3 class="fw-bold text-success">{{ $attemptsCount }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center p-3">
                    <i class="bi bi-bar-chart-line-fill fs-3 text-warning mb-2"></i>
                    <h6 class="text-muted">Середній бал спроб</h6>
                    <h3 class="fw-bold text-warning">{{ round($averageScore, 1) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0 rounded-4 bg-light-subtle text-center p-3">
                    <i class="bi bi-trophy fs-3 text-danger mb-2"></i>
                    <h6 class="text-muted">Максимальний бал</h6>
                    <h3 class="fw-bold text-danger">{{ round($maxScore, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4">
                    <div class="card-header fw-semibold text-uppercase text-center">
                        <i class="bi bi-pie-chart-fill me-2"></i>Розподіл мов програмування
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm rounded-4 mb-4">
                    <div class="card-header fw-semibold text-uppercase text-center">
                        <i class="bi bi-diagram-3-fill me-2"></i>Розподіл класів (радіальна діаграма)
                    </div>
                    <div class="card-body">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm rounded-4">
                    <div class="card-header fw-semibold text-uppercase text-center">
                        <i class="bi bi-stars me-2"></i>Top 5 учасників
                    </div>
                    <div class="card-body p-1 m-2">
                        <ul class="list-group list-group-flush">
                            @foreach($topParticipants as $user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person-circle me-2 text-primary"></i>{{ $user->full_name }}</span>
                                <span class="badge bg-primary rounded-pill">{{ round($user->max_score, 2) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm rounded-4">
                    <div class="card-header fw-semibold text-uppercase text-center">
                        <i class="bi bi-graph-up-arrow me-2"></i>Динаміка активності (дні)
                    </div>
                    <div class="card-body">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: @json($langLabels),
                datasets: [{
                    data: @json($langCounts),
                    backgroundColor: ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949']
                }]
            },
            options: {
                responsive: true
            }
        });

        new Chart(document.getElementById('radarChart'), {
            type: 'radar',
            data: {
                labels: @json($classLabels),
                datasets: [{
                    label: 'Кількість учасників',
                    data: @json($classCounts),
                    backgroundColor: 'rgba(78, 121, 167, 0.2)',
                    borderColor: '#4e79a7',
                    pointBackgroundColor: '#4e79a7',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4e79a7'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        new Chart(document.getElementById('lineChart'), {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Спроби',
                    data: @json($counts),
                    borderColor: '#f28e2b',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

