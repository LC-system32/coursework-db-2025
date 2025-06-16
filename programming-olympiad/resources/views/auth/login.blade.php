@extends('layout')

@section('title', 'Вхід')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Вхід</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
    @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Ім'я</label>
            <input type="name" name="name" id="name" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Увійти</button>
    </form>
</div>
@endsection