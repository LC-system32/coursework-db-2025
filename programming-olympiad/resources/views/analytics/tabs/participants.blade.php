<div class="tab-pane fade show active" id="participants" role="tabpanel">
    <div class="row justify-content-center row-cols-1 row-cols-md-2 g-4 mb-5">
        <div class="col d-flex justify-content-center">
            <div class="card shadow-sm border-0 rounded-4 text-center p-4 bg-white" style="width: 20rem;">
                <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                <h6 class="text-secondary mb-1">Загальна кількість учасників</h6>
                <h2 class="fw-bold text-primary">{{ $totalParticipants }}</h2>
            </div>
        </div>
        <div class="col d-flex justify-content-center">
            <div class="card shadow-sm border-0 rounded-4 text-center p-4 bg-white" style="width: 20rem;">
                <i class="bi bi-code-slash fs-1 text-success mb-3"></i>
                <h6 class="text-secondary mb-1">Найпопулярніша мова</h6>
                <h2 class="fw-bold text-success">{{ $popularLang }}</h2>
                <small class="text-secondary">Використань: {{ $popularLangCount }}</small>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-4 mb-5">
        <div class="col-md-5 d-flex justify-content-center">
            <div class="card shadow-sm border-0 rounded-4 w-100">
                <div class="card-header bg-light fw-semibold text-center text-uppercase">
                    <i class="bi bi-bar-chart-line me-2 text-warning"></i>Топ-5 за середнім балом
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($topByAvg as $p)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span>{{ $p['full_name'] }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $p['avg_score'] }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">Немає даних</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-md-5 d-flex justify-content-center">
            <div class="card shadow-sm border-0 rounded-4 w-100">
                <div class="card-header bg-light fw-semibold text-center text-uppercase">
                    <i class="bi bi-trophy me-2 text-danger"></i>Топ-5 за максимальним балом
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($topByMax as $p)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <span>{{ $p['full_name'] }}</span>
                        <span class="badge bg-danger rounded-pill">{{ $p['max_score'] }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">Немає даних</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>