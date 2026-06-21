@extends('layouts.app')
@section('title', 'Главная')
@section('content')

<a name="company"></a>
<h1 class="company">О компании</h1>
<p class="text-description">«Программы сферы образования» — это компания, которая специализируется на проведении курсов программирования для всех уровней. Наша миссия — помочь студентам и профессионалам приобрести навыки, востребованные на рынке труда в сфере информационных технологий. Мы предлагаем широкий спектр образовательных программ, от основ программирования до продвинутых технологий разработки. Наши опытные преподаватели и индивидуальный подход к каждому студенту помогут им достичь успеха в своей карьере в IT-индустрии.</p>
<img src="{{ asset('images/XXL.webp') }}" style="height: 480px;" alt="Компания">

@if (session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

<a name="courses"></a>
<h1 class="courses mt-4">Курсы</h1>

<form class="categories_hold mb-3" action="{{ route('index') }}" method="get">
    <div class="d-flex align-items-center gap-2">
        <label for="category" class="mb-0">Категория:</label>
        <select id="category" name="category" class="form-select w-auto">
            <option value="">— Все категории —</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->title }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-primary" type="submit">Найти</button>
        @if (request('category'))
            <a href="{{ route('index') }}" class="btn btn-outline-secondary">Сбросить</a>
        @endif
    </div>
</form>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
    @forelse ($courses as $course)
        <div class="col">
            <div class="card h-100">
                @if ($course->image)
                    <img src="{{ asset($course->image) }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $course->title }}">
                @endif
                <div class="card-header">{{ $course->title }}</div>
                <div class="card-body">
                    <p class="card-text">{{ Str::limit($course->description, 120) }}</p>
                    <ul class="list-unstyled small text-muted">
                        <li>Категория: {{ $course->category->title ?? '—' }}</li>
                        <li>Длительность: {{ $course->duration }} ч.</li>
                        <li>Стоимость: {{ number_format($course->cost, 2, ',', ' ') }} ₽</li>
                    </ul>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('detail', $course) }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <p class="text-muted">Курсы не найдены.</p>
        </div>
    @endforelse
</div>

{{ $courses->links('pagination::bootstrap-4') }}

<hr class="my-5">
<h2 id="apply">Записаться на курс</h2>
<form action="{{ route('application.store') }}" method="post" class="row g-3 mb-5" style="max-width:560px;">
    @csrf
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
        <label for="fio" class="form-label">ФИО клиента</label>
        <input type="text" id="fio" name="fio" class="form-control @error('fio') is-invalid @enderror"
               value="{{ old('fio') }}" required maxlength="150">
        @error('fio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="email" class="form-label">Почта клиента</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email') }}" required maxlength="150">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="course" class="form-label">Курс</label>
        <select id="course" name="course" class="form-select @error('course') is-invalid @enderror" required>
            <option value="">— Выберите курс —</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" {{ old('course') == $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
        @error('course')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">Отправить заявку</button>
    </div>
</form>
@endsection
