@extends('layouts.app')
@section('title', 'Quizzes — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="page-header">
    <h1>Tavi <em>Quizzes</em></h1>
    <p>Izvēlies tēmu un izaicini sevi.</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="num">{{ $quizzes->count() }}</div>
        <div class="lbl">Piejamie quizzes</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ $categories->count() }}</div>
        <div class="lbl">Katagorijas</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ Auth::user()->quizAttempts()->count() }}</div>
        <div class="lbl">Tavi meiģinajumi</div>
    </div>
</div>

@if($quizzes->isEmpty())
    <div class="empty">
        <div class="icon">📭</div>
        <p>quizzes vēl nav pieejamas. Pārbaudiet vēlāk!</p>
    </div>
@else
    <div class="quiz-grid">
        @foreach($quizzes as $quiz)
        <a href="{{ route('quiz.show', $quiz) }}" class="quiz-card">

            <div class="card-top">
                <div class="card-icon">{{ $quiz->category->icon ?? '📝' }}</div>
                <span class="diff-badge diff-{{ $quiz->difficulty }}">
                    {{ $quiz->difficulty }}
                </span>
            </div>

            <div class="card-title">{{ $quiz->title }}</div>

            @if($quiz->description)
                <div class="card-desc">{{ Str::limit($quiz->description, 90) }}</div>
            @endif

            <div class="card-meta">
                <span> {{ $quiz->questions->count() }} questions</span>
                @if($quiz->time_limit)
                    <span> {{ $quiz->time_limit / 60 }} min</span>
                @endif
                <span> {{ $quiz->category->name }}</span>
            </div>

        </a>
        @endforeach
    </div>
@endif

@endsection