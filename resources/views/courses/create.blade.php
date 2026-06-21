@extends('layouts.app')
@section('title', 'Добавить курс')
@section('content')

<nav aria-label="breadcrumb" class="mt-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Мои заявки</a></li>
        <li class="breadcrumb-item active">Добавить курс</li>
    </ol>
</nav>

<h1 class="mb-4">Добавить курс</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('home.course.store') }}" method="post" class="row g-3" style="max-width:600px;">
    @csrf
    <div class="col-12">
        <label for="title" class="form-label">Название курса</label>
        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title') }}" required maxlength="100">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label for="category_id" class="form-label">Категория</label>
        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">— Выберите —</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->title }}
                </option>
            @endforeach
        </select>
        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label for="duration" class="form-label">Длительность (ч.)</label>
        <input type="number" id="duration" name="duration" class="form-control @error('duration') is-invalid @enderror"
               value="{{ old('duration') }}" required min="1">
        @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
        <label for="cost" class="form-label">Стоимость (₽)</label>
        <input type="number" id="cost" name="cost" step="0.01"
               class="form-control @error('cost') is-invalid @enderror"
               value="{{ old('cost') }}" required min="0">
        @error('cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Описание</label>
        <textarea id="description" name="description" rows="5"
                  class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label for="image" class="form-label">Путь к изображению</label>
        <input type="text" id="image" name="image" class="form-control @error('image') is-invalid @enderror"
               value="{{ old('image') }}" placeholder="images/example.jpg">
        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('home.index') }}" class="btn btn-outline-secondary ms-2">Отмена</a>
    </div>
</form>
@endsection
