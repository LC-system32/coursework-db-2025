@extends('layout')

@section('title', $title)

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .clickable-row {
        cursor: pointer;
    }

    .clickable-row td {
        pointer-events: auto;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" defer></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.6/i18n/uk.json" defer></script>

<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        const initTable = () => {
            const table = $('#universalTable').DataTable({
                paging: true,
                info: true,
                searching: false,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/uk.json"
                },
                initComplete: function() {
                    document.getElementById('tableLoader').style.display = 'none';
                    document.getElementById('tableWrapper').style.display = 'block';
                }
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();

                $.get(`/filter/{{ $pageType }}`, $(this).serialize(), function(data) {
                    table.clear();

                    if (data.length === 0) return table.draw();

                    const columns = @json(array_keys($columns));

                    data.forEach((row, index) => {
                        const rowData = [`<td>${index + 1}</td>`];
                        columns.forEach(key => {
                            rowData.push(`<td>${row[key] ?? 'Інформація відсутня'}</td>`);
                        });

                        const trClass = "{{ isset($routeName) ? 'clickable-row' : '' }}";
                        const href = "{{ isset($routeName) ? route($routeName, ['id' => '__ID__', 'backUrl' => url()->full()]) : '' }}".replace('__ID__', row['id']);

                        const fullRowHtml = `<tr class="${trClass}" data-href="${href}">${rowData.join('')}</tr>`;

                        table.row.add($(fullRowHtml));
                    });

                    $('#filterError').addClass('d-none');
                    table.draw();
                }).fail(function(xhr) {
                    let message = 'Сталася невідома помилка.';

                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const json = JSON.parse(xhr.responseText);
                            message = json.message || xhr.responseText;
                        } catch (_) {
                            message = xhr.responseText;
                        }
                    }

                    $('#filterError')
                        .removeClass('d-none')
                        .text(`Помилка: ${message}`);
                });
            });

            $(document).on('click', '.clickable-row', function() {
                const href = $(this).data('href');
                if (href) window.location.href = href;
            });
        };

        if (window.jQuery && $.fn.dataTable) {
            initTable();
        } else {
            const checkLoaded = setInterval(() => {
                if (window.jQuery && $.fn.dataTable) {
                    clearInterval(checkLoaded);
                    initTable();
                }
            }, 50);
        }
    });
</script>
@endpush

@section('content')
<div class="container py-5">
    <h2 class="mb-5 text-center fw-bold">{{ $title }}</h2>
    @include('universal-pages.filter-form')

    <div id="filterError" class="alert alert-danger d-none text-center mt-3"></div>

    <div id="tableLoader" class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Завантаження...</span>
        </div>
    </div>


    <div id="tableWrapper" style="display: none;">
        <div class="card shadow rounded-4">
            <div class="card-body">
                <table id="universalTable" class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>№</th>
                            @foreach($columns as $header)
                            <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $index => $row)
                        <tr @if(isset($routeName)) class="clickable-row" data-href="{{ route($routeName, ['id' => $row['id'], 'backUrl' => url()->full()]) }}" @endif>
                            <td>{{ $index + 1 }}</td>
                            @foreach(array_keys($columns) as $key)
                            <td>{{ $row[$key] ?? 'Інформація відсутня' }}</td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="text-muted">Немає даних</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection