<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-header fw-semibold text-center">
                <i class="bi bi-bar-chart-fill me-2"></i> % високих балів по тестах
            </div>
            <div class="card-body">
                <canvas id="highScoreChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-header fw-semibold text-center">
                <i class="bi bi-slash-circle me-2"></i> Найменш популярні тести
            </div>
            <div class="card-body">
                <canvas id="leastAttemptedChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-header fw-semibold text-center">
                <i class="bi bi-calendar-range me-2"></i> Щоденна активність
            </div>
            <div class="card-body">
                <canvas id="dailyActivityChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
            <div class="card-header fw-semibold text-center">
                <i class="bi bi-code-slash me-2"></i> Кількість мов на тест
            </div>
            <div class="card-body">
                <canvas id="langPerTestChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm rounded-4">
            <div class="card-header fw-semibold text-center">
                <i class="bi bi-x-circle-fill me-2"></i> Рейтинг провальних тестів
            </div>
            <div class="card-body">
                <canvas id="failRateChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const colors = ['#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'];

    new Chart(document.getElementById('highScoreChart'), {
        type: 'bar',
        data: {
            labels: @json($highScoreLabels),
            datasets: [{
                label: '% високих балів',
                data: @json($highScoreData),
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true, max: 100 }
            }
        }
    });

    new Chart(document.getElementById('leastAttemptedChart'), {
        type: 'bar',
        data: {
            labels: @json($leastAttemptedLabels),
            datasets: [{
                label: 'Кількість спроб',
                data: @json($leastAttemptedData),
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('dailyActivityChart'), {
        type: 'line',
        data: {
            labels: @json($dailyLabels),
            datasets: [{
                label: 'Спроби на день',
                data: @json($dailyData),
                borderColor: '#4e79a7',
                backgroundColor: 'rgba(78,121,167,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('langPerTestChart'), {
        type: 'bar',
        data: {
            labels: @json($langPerTestLabels),
            datasets: [{
                label: 'Мов на тест',
                data: @json($langPerTestData),
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'x',
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('failRateChart'), {
        type: 'bar',
        data: {
            labels: @json($failRateLabels),
            datasets: [{
                label: 'Рівень провалу (%)',
                data: @json($failRateData),
                backgroundColor: '#e15759'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });
</script>
