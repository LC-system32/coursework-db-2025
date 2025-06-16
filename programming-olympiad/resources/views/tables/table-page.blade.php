@extends('layout')

@section('title', $title)

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    td[contenteditable="true"] {
        background-color: #fff8dc;
        border: 1px dashed #aaa;
        max-width: 200px;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    table td, table th {
        vertical-align: middle;
        word-break: break-word;
        white-space: normal !important;
    }

    .table-responsive {
        overflow-x: auto;
    }

    #noDataMessage {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" defer></script>

<script defer>
document.addEventListener("DOMContentLoaded", () => {
    const tableName = "{{ $pageType }}";

    const showError = (message) => {
        $('#errorMessage').removeClass('d-none').text(message);
    };

    const clearError = () => {
        $('#errorMessage').addClass('d-none').text('');
    };

    const initTable = () => {
        const hasRows = $('#universalTable tbody tr').length > 1;

        if (!hasRows) {
            $('#tableLoader').hide();
            $('#tableWrapper').hide();
            $('#noDataMessage').show();
            return;
        }

        const table = $('#universalTable').DataTable({
            paging: true,
            info: true,
            searching: false,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/uk.json"
            },
            initComplete: () => {
                $('#tableLoader').hide();
                $('#tableWrapper').show();
            }
        });

        $(document).on('click', '.btn-delete', function() {
            if (!confirm('Ви впевнені, що хочете видалити цей запис?')) return;

            clearError();

            const row = $(this).closest('tr');
            const id = row.data('id');

            $.ajax({
                url: `http://localhost:3000/${tableName}/${id}`,
                method: 'DELETE',
                success: () => {
                    table.row(row).remove().draw();
                },
                error: (xhr) => {
                    console.error('Delete error:', xhr.responseText);
                    showError('Помилка під час видалення.');
                }
            });
        });

        $(document).on('click', '.btn-save', function() {
            clearError();

            const row = $(this).closest('tr');
            const id = row.data('id');
            const data = {};

            row.find('td[contenteditable]').each(function() {
                const td = $(this);
                const column = td.data('column');
                const value = td.text().trim();
                if (value !== td.data('original').toString().trim()) {
                    data[column] = value;
                }
            });

            $.ajax({
                url: `http://localhost:3000/${tableName}/${id}`,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: () => {
                    row.find('.btn-save').addClass('d-none');
                    row.find('td[contenteditable]').each(function() {
                        const td = $(this);
                        td.data('original', td.text().trim());
                        td.css('background-color', '#d4edda');
                        setTimeout(() => td.css('background-color', ''), 500);
                    });
                },
                error: (xhr) => {
                    console.error('Update error:', xhr.responseText);
                    showError('Помилка при збереженні.');
                }
            });
        });

        $(document).on('input', 'td[contenteditable]', function() {
            const row = $(this).closest('tr');
            const changed = row.find('td[contenteditable]').toArray().some(td => {
                const el = $(td);
                return el.text().trim() !== el.data('original')?.toString().trim();
            });

            row.find('.btn-save').toggleClass('d-none', !changed);
        });

        $(document).on('click', '.btn-add', function () {
            clearError();

            const row = $(this).closest('tr');
            const data = {};
            let valid = true;

            row.find('td[contenteditable]').each(function () {
                const td = $(this);
                const column = td.data('column');
                const value = td.text().trim();

                if (!value && column !== 'id') {
                    td.css('background-color', '#f8d7da');
                    valid = false;
                } else {
                    td.css('background-color', '');
                }

                if (column !== 'id') {
                    data[column] = value;
                }
            });

            if (!valid) {
                showError("Заповніть всі поля перед додаванням.");
                return;
            }

            $.ajax({
                url: `http://localhost:3000/${tableName}`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: () => {
                    location.reload();
                },
                error: (xhr) => {
                    console.error('Create error:', xhr.responseText);
                    showError('Помилка при створенні запису.');
                }
            });
        });
    };

    const waitForDT = setInterval(() => {
        if (window.jQuery && $.fn.DataTable) {
            clearInterval(waitForDT);
            initTable();
        }
    }, 50);
});
</script>
@endpush

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-5 fw-bold">{{ $title }}</h2>

    <div id="errorMessage" class="alert alert-danger d-none fw-semibold text-center"></div>

    <div id="tableLoader" class="text-center py-4">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Завантаження...</span>
        </div>
    </div>

    <div id="noDataMessage" class="alert alert-warning text-center fw-semibold">
        Немає даних для відображення.
    </div>

    <div id="tableWrapper" style="display: none;">
        <div class="card shadow rounded-4">
            <div class="card-body p-2 table-responsive">
                <table id="universalTable" class="table table-hover table-bordered align-middle text-center mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>№</th>
                            @foreach($columns as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                            <th>Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                        <tr data-id="{{ $row['id'] ?? '--'}}">
                            <td>{{ $i + 1 }}</td>
                            @foreach(array_keys($columns) as $key)
                            <td contenteditable="{{ $key !== 'id' ? 'true' : 'false' }}"
                                data-column="{{ $key }}"
                                data-original="{{ $row[$key] }}">
                                {{ $row[$key] }}
                            </td>
                            @endforeach
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-outline-success btn-save d-none" title="Зберегти">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" title="Видалити">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($columns) + 2 }}" class="text-muted">Немає даних</td>
                        </tr>
                        @endforelse

                        <tr class="new-entry-row">
                            <td>—</td>
                            @foreach(array_keys($columns) as $key)
                            <td contenteditable="{{ $key !== 'id' ? 'true' : 'false' }}"
                                data-column="{{ $key }}"></td>
                            @endforeach
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary btn-add" title="Додати">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
