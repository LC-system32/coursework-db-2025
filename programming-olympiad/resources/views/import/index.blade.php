@extends('layout')

@section('title', 'Імпорт результатів')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-semibold" style="font-size: 2.2rem;">
            <i class="bi bi-upload me-2 text-primary"></i> Імпорт даних
        </h2>
        <p class="text-muted" style="font-size: 1.1rem;">
            Завантажте файл <strong>JSON</strong>, щоб імпортувати учасників, вчителів, результати тестів.
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger text-center">
        <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
    </div>
@endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow rounded-4 h-100 bg-light">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title text-center mb-3">
                            <i class="bi bi-people-fill text-primary fs-3 me-2"></i>
                            Учасники
                        </h5>
                        <p class="text-muted small text-center mb-4">
                            Очікується файл із масивом об’єктів. Кожен об’єкт повинен містити <code>full_name</code>, <code>school</code>, <code>class</code>, <code>teacher</code>.
                        </p>
                        <form method="POST" action="{{ route('import.importFile', ['file' => 'participants']) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="participants_file" class="form-label fw-medium">JSON-файл</label>
                                <input class="form-control rounded-3 shadow-sm" type="file" id="participants_file" name="json_file" accept=".json" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary rounded-3 shadow-sm">
                                    <i class="bi bi-cloud-arrow-up me-2"></i>Імпортувати
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow rounded-4 h-100 bg-light">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title text-center mb-3">
                            <i class="bi bi-person-vcard-fill text-primary fs-3 me-2 mb-0"></i>
                            Вчителі
                        </h5>
                        <p class="text-muted small text-center mb-4">
                            Формат: масив об’єктів з полями <code>full_name</code>, <code>school</code>. Усі поля обов’язкові.
                        </p>
                        <form method="POST" action="{{ route('import.importFile', ['file' => 'teachers']) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="teachers_file" class="form-label fw-medium">JSON-файл</label>
                                <input class="form-control rounded-3 shadow-sm" type="file" id="teachers_file" name="json_file" accept=".json" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary rounded-3 shadow-sm">
                                    <i class="bi bi-cloud-arrow-up me-2"></i>Імпортувати
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow rounded-4 h-100 bg-light">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title text-center mb-3">
                            <i class="bi bi-terminal-fill text-primary fs-3 me-2"></i>
                            Результати тестів
                        </h5>
                        <p class="text-muted small text-center mb-4">
                            Кожен запис повинен містити <code>participant</code>, <code>score</code>, <code>language</code>, <code>code</code>, <code>submitted</code>.
                        </p>
                        <form method="POST" action="{{ route('import.importFile', ['file' => 'submissions']) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="submissions_file" class="form-label fw-medium">JSON-файл</label>
                                <input class="form-control rounded-3 shadow-sm" type="file" id="submissions_file" name="json_file" accept=".json" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-primary rounded-3 shadow-sm text-dark">
                                    <i class="bi bi-cloud-arrow-up me-2"></i>Імпортувати
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
