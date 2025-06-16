@extends('layout')

@section('content')
<div class="container py-4">
    <h2 class="fw-semibold mb-4">
        <i class="bi bi-bar-chart-steps me-2"></i>Аналітика
    </h2>

    <ul class="nav nav-tabs" id="analyticsTabs" role="tablist">
    <li class="nav-item">
            <a class="nav-link" id="participants-tab" data-bs-toggle="tab"
               href="#participants" role="tab" data-url="{{ route('analytics.participants') }}">
                Учасники
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="teachers-tab" data-bs-toggle="tab"
               href="#teachers" role="tab" data-url="{{ route('analytics.teachers') }}">
                Вчителі
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" id="submissions-tab" data-bs-toggle="tab"
               href="#submissions" role="tab" data-url="{{ route('analytics.submissions') }}">
               Спроби
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tests-tab" data-bs-toggle="tab"
               href="#tests" role="tab" data-url="{{ route('analytics.tests') }}">
               Тести
            </a>
        </li>
    </ul>

    <div class="tab-content mt-3" id="analyticsTabsContent">
        <div class="tab-pane fade" id="teachers" role="tabpanel">Завантаження...</div>
        <div class="tab-pane fade" id="tests" role="tabpanel">Завантаження...</div>
        <div class="tab-pane fade" id="participants" role="tabpanel">Завантаження...</div>
        <div class="tab-pane fade" id="submissions" role="tabpanel">Завантаження...</div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll('#analyticsTabs a');
    const contentIdFromHash = window.location.hash.replace('#','') || 'participants';

    function activateTab(id) {
        const triggerEl = document.querySelector(`#analyticsTabs a[href="#${id}"]`);
        if (!triggerEl) return;
        const tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }

    tabs.forEach(a => {
        a.addEventListener('shown.bs.tab', e => {
            const paneId = e.target.getAttribute('href').substring(1);
            const pane   = document.getElementById(paneId);
            const url    = e.target.dataset.url;

            history.replaceState(null, null, `#${paneId}`);

            if (!pane.dataset.loaded) {
                fetch(url)
                    .then(r => r.text())
                    .then(html => {
                        pane.innerHTML = html;
                        pane.dataset.loaded = 'true';

                        pane.querySelectorAll('script').forEach(old => {
                            const s = document.createElement('script');
                            s.text = old.text;
                            document.body.appendChild(s).remove();
                        });
                    });
            }
        });
    });

    activateTab(contentIdFromHash);
});
</script>
@endpush
