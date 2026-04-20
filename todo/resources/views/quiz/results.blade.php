@extends('layouts.app')
@section('title', 'Result — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endsection

@section('content')

@php
    $pct   = $attempt->max_score > 0
               ? round(($attempt->score / $attempt->max_score) * 100)
               : 0;
    $grade = $pct >= 80 ? '🏆 Excellent!'
           : ($pct >= 60 ? '👍 Good job!'
           : '📚 Keep practicing!');
@endphp

<div class="result-hero">
    <div class="score-ring" style="--pct: {{ $pct }}">
        <span class="score-num">{{ $pct }}%</span>
    </div>

    <h1 class="result-title">{{ $grade }}</h1>
    <p class="result-sub">You scored {{ $attempt->score }} out of {{ $attempt->max_score }} points</p>

    <div class="result-badges">
        <div class="r-badge">
            <strong>{{ $attempt->score }}</strong>
            Points scored
        </div>
        <div class="r-badge">
            <strong>{{ $attempt->userAnswers->where('is_correct', true)->count() }}</strong>
            Correct answers
        </div>
        @if($attempt->time_taken)
        <div class="r-badge">
            <strong>{{ gmdate('i:s', $attempt->time_taken) }}</strong>
            Time taken
        </div>
        @endif
    </div>
</div>

<div class="review-section">
    <p class="review-title">Review your answers</p>

    @foreach($attempt->userAnswers as $ua)
    <div class="review-item {{ $ua->is_correct ? 'correct' : 'incorrect' }}">
        <div class="review-q">{{ $ua->question->question_text }}</div>
        <div class="review-ans {{ $ua->is_correct ? 'ok' : 'bad' }}">
            {{ $ua->is_correct ? '✔' : '✘' }}
            Your answer: {{ $ua->answer->answer_text }}
        </div>
    </div>
    @endforeach
</div>

<div class="cta-row">
    <a href="{{ route('quiz.show', $attempt->quiz_id) }}" class="btn btn-ghost">Try again</a>
    <a href="{{ route('quiz.index') }}"                   class="btn btn-primary">All quizzes →</a>
</div>

@endsection