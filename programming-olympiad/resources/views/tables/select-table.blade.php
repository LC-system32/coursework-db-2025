@extends('layout')

@section('title', 'Вибір таблиці')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Виберіть таблицю для керування записами</h2>
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
    <div class="row justify-content-center">
        @foreach ($tables as $key => $table)
        <div class="col-sm-12 col-md-6 col-lg-3 d-flex justify-content-center mb-4">
            <div class="card h-100 hover-card" style="width: 300px;">
                <div class="card-body text-center d-flex flex-column">
                    <h5 class="card-title fw-bold mb-2">{{ $table['name'] }}</h5>
                    <p class="card-text text-secondary flex-grow-1">{{ $table['comment'] }}</p>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-around py-3">

                    <a href="{{ route( 'table.list', ['table' => $table['name']]) }}"
                        class="btn btn-soft-primary rounded-circle" title="Переглянути / Редагувати / Додати">
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                    <form action="{{ route('table.destroyAll', ['table' => $table['name']]) }}" method="POST"
                        onsubmit="return confirm('Ви впевнені, що хочете видалити всі записи?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger rounded-circle" title="Видалити таблицю">
                            <i class="bi bi-trash fs-4"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .hover-card {
        border-radius: 1rem;
        border: 2px solid #e0e0e0;
        background-color: #ffffff;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-footer {
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }

    .hover-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #007bff;
        background-color: #f0f8ff;
    }


    .btn-soft-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .btn-soft-primary {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .btn-soft-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .btn-soft-success:hover {
        background-color: #c3e6cb;
        border-color: #b1dfbb;
    }

    .btn-soft-primary:hover {
        background-color: #a8dadc;
        border-color: #9bd0d4;
    }

    .btn-soft-danger:hover {
        background-color: #f5c6cb;
        border-color: #f1b0b7;
    }

    .card-title {
        color: #222222;
    }

    .card-text {
        font-size: 1rem;
    }
</style>
@endsection