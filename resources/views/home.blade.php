@extends('layouts.app')
@section('title', 'Мои заявки')
@section('content')

<div class="d-flex justify-content-between align-items-center mt-3 mb-4">
    <h1 class="mb-0">Мои заявки</h1>
    <a href="{{ route('home.course.create') }}" class="btn btn-primary">Добавить курс</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($applications->isEmpty())
    <div class="alert alert-info">У вас пока нет заявок. <a href="{{ route('index') }}#apply">Записаться на курс</a></div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Курс</th>
                    <th>Категория</th>
                    <th>Дата заявки</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                <tr>
                    <td>{{ $application->id }}</td>
                    <td>
                        <a href="{{ route('detail', $application->course) }}">
                            {{ $application->course->title ?? '—' }}
                        </a>
                    </td>
                    <td>{{ $application->course->category->title ?? '—' }}</td>
                    <td>{{ $application->application_date?->format('d.m.Y H:i') }}</td>
                    <td>
                        @php
                            $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                            $labels = ['pending' => 'Ожидает', 'approved' => 'Одобрена', 'rejected' => 'Отклонена'];
                        @endphp
                        <span class="badge bg-{{ $badges[$application->status] ?? 'secondary' }}">
                            {{ $labels[$application->status] ?? $application->status }}
                        </span>
                    </td>
                    <td>
                        <form action="{{ route('home.application.destroy', $application) }}" method="POST"
                              onsubmit="return confirm('Удалить заявку?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $applications->links('pagination::bootstrap-4') }}
@endif
@endsection
