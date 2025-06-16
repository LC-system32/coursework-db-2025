@extends('layout')

@section('title', $title ?? 'Деталі')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .clickable-row {
        cursor: pointer;
    }

    .clickable-row td {
        pointer-events: auto;
    }

    .table th,
    .table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    pre code {
        font-size: 0.9rem;
        word-break: break-word;
        white-space: pre-wrap;
    }

    .card {
        background: #fefefe;
    }

    <style>.table td .text-truncate {
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
</style>
</style>
@endpush

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-lg border-0 rounded-4 w-100" style="max-width: 1400px;">
        <div class="card-header bg-light text-center fs-4 fw-semibold rounded-top-4 py-3 d-flex justify-content-between align-items-center px-4 flex-wrap">
            <span >{{ $title ?? 'Деталі' }}</span>
            @if(!empty($verdict_code))
            <span class="badge
                    @if($verdict_code === 'OK') bg-success
                    @elseif($verdict_code === 'WA') bg-danger
                    @elseif($verdict_code === 'TLE') bg-warning text-dark
                    @else bg-secondary
                    @endif
                    fs-6 px-3 py-2 rounded-pill">
                {{ $verdict_code }}
            </span>
            @endif
        </div>

        <div class="row g-0">
            <div class="col-md-4 bg-body-secondary p-4 border-end d-flex flex-column">
                <h5 class="fw-bold mb-4 text-center">Загальні відомості</h5>
                <ul class="list-group list-group-flush rounded overflow-hidden shadow-sm">
                    @foreach($info ?? [] as $label => $value)
                    <li class="list-group-item py-3">
                        <strong>{{ $label }}:</strong><br>{{ $value ?? '—' }}
                    </li>
                    @endforeach
                </ul>

                @if(request()->has('backUrl'))
                <a href="{{ request('backUrl') }}" class="btn btn-outline-secondary mt-5 w-100 rounded-pill transition-all">
                    ← Повернутися назад
                </a>
                @endif
            </div>

            <div class="col-lg-8 p-4 bg-white rounded-end-4">
                @if(!empty($code))
                <div class="bg-light p-4 rounded-4 border mb-4 shadow-sm">
                    <h5 class="fw-bold text-primary mb-2">
                        <i class="bi bi-clipboard-check me-2"></i>{{ $test_name }}
                    </h5>
                    <p class="text-muted mb-0">{{ $test_description }}</p>
                </div>

                <h5 class="fw-bold mb-4 text-center">Вихідний код</h5>
                <div class="bg-light p-3 rounded-3 border" style="max-height: 600px; overflow: auto;">
                    <pre class="mb-0"><code>{{ $code }}</code></pre>
                </div>

                @elseif(!empty($table))
                <h5 class="fw-bold mb-4 text-center">Історія спроб</h5>
                <div class="table-responsive border rounded-3 shadow-sm" style="max-height: 450px; overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle text-center mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th style="width: 50px;">№</th>
                                @foreach($table['columns'] as $column)
                                <th>{{ $column }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($table['rows'] as $index => $row)
                            <tr @if(isset($row['href'])) class="clickable-row" data-href="{{ $row['href'] }}" @endif>
                                <td>{{ $index + 1 }}</td>
                                @foreach($table['columns'] as $key => $label)
                                <td>
                                    <div class="text-truncate" style="max-width: 180px;" title="{{ $row[$key] ?? '—' }}">
                                        {{ $row[$key] ?? '—' }}
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($table['columns']) + 1 }}" class="text-muted">Інформація відсутня</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">Немає даних для відображення</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', () => {
            window.location.href = row.dataset.href;
        });
    });
</script>
@endpush