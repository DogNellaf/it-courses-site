@extends('layouts.app')
@section('title', $course->title)
@section('content')

<nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('index') }}">Курсы</a></li>
        <li class="breadcrumb-item active">{{ $course->title }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-8">
        <h1>{{ $course->title }}</h1>
        <p class="text-muted">
            Категория:
            <span class="badge bg-secondary">{{ $course->category->title ?? '—' }}</span>
        </p>
        <p>{{ $course->description }}</p>
    </div>
    <div class="col-md-4">
        <div class="card">
            @if ($course->image)
                <img src="{{ asset($course->image) }}" class="card-img-top" style="object-fit:cover;max-height:220px;" alt="{{ $course->title }}">
            @endif
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <strong>Длительность:</strong> {{ $course->duration }} ч.
                    </li>
                    <li class="mb-2">
                        <strong>Стоимость:</strong> {{ number_format($course->cost, 2, ',', ' ') }} ₽
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<hr class="my-4">
<h3>Записаться на курс</h3>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form action="{{ route('application.store') }}" method="post" class="row g-3" style="max-width:500px;">
    @csrf
    <input type="hidden" name="course" value="{{ $course->id }}">
    @if ($errors->any())
        <div class="col-12">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    <div class="col-12">
        <label for="fio" class="form-label">ФИО</label>
        <input type="text" id="fio" name="fio" class="form-control @error('fio') is-invalid @enderror"
               value="{{ old('fio') }}" required maxlength="150">
        @error('fio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required maxlength="150">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">Отправить заявку</button>
        <a href="{{ route('index') }}" class="btn btn-outline-secondary ms-2">Назад</a>
    </div>
</form>
@endsection
