@extends('layout')

@section('title', 'Головна')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Облік результатів олімпіади з програмування</h1>
    </div>

    <div class="d-flex justify-content-center flex-wrap gap-4">
        @php
        $cards = [
            ['icon' => 'people-fill', 'title' => 'Учасники', 'text' => 'Інформація про школярів: ПІБ, клас, школа, вчитель.', 'href' => '/participants'],
            ['icon' => 'terminal', 'title' => 'Спроби учасників', 'text' => 'Бали, код, мова програмування для кожної спроби.', 'href' => '/submissions'],
            ['icon' => 'upload', 'title' => 'Імпорт даних', 'text' => 'Завантаження даних з JSON файлів.', 'href' => '/import'],
            ['icon' => 'person-vcard', 'title' => 'Вчителі', 'text' => 'ПІБ, заклад освіти та учні, які брали участь в олімпіаді.', 'href' => '/teachers'],
            ['icon' => 'bar-chart-line', 'title' => 'Аналітика', 'text' => 'Звіти за вчителями, результатами та тестами.', 'href' => '/analytics'],
            ['icon' => 'pencil-square', 'title' => 'Управління даними', 'text' => 'Редагування записів таблиць.', 'href' => '/tables'],
        ];
        @endphp

        @foreach ($cards as $card)
            @php
            $adminOnly = in_array($card['href'], ['/import', '/tables']);
            @endphp

            @if (!$adminOnly || (auth()->check() && auth()->user()->is_admin))
                <div class="col-md-6 row col-lg-4 d-flex justify-content-center">
                    <a href="{{ $card['href'] }}" class="text-decoration-none text-dark w-100" style="max-width: 450px;">
                        <div class="card shadow-sm border-0 h-100 hover-card mb-2">
                            <div class="card-body d-flex flex-column align-items-center text-center mt-2">
                                <div class="mb-2">
                                    <i class="bi bi-{{ $card['icon'] }} fs-1 text-primary"></i>
                                </div>
                                <h5 class="card-title">{{ $card['title'] }}</h5>
                                <p class="card-text">{{ $card['text'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</div>

<style>
    .hover-card:hover {
        background-color: #f0f8ff;
        transition: background-color 0.3s ease;
    }
</style>
@endsection
