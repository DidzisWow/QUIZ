@extends('layouts.app')
@section('title', $quiz->title . ' — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')

<div class="quiz-header">
    <div class="quiz-header-left">
        <h1>{{ $quiz->title }}</h1>

        @if($quiz->description)
            <p>{{ $quiz->description }}</p>
        @endif

        <div class="quiz-meta">
            <span class="meta-pill">📚 {{ $quiz->questions->count() }} questions</span>
            <span class="meta-pill">🏷 {{ $quiz->category->name }}</span>
            <span class="meta-pill diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
        </div>
    </div>

    @if($quiz->time_limit)
        <div id="timer-box">
            <div class="t-label">Time left</div>
            <div id="timer-display">--:--</div>
        </div>
    @endif
</div>

<div class="progress-bar">
    <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
</div>

<form method="POST" action="{{ route('quiz.submit', $quiz) }}" id="quiz-form">
    @csrf

    @foreach($quiz->questions as $i => $question)
    <div class="question-block">

        <div class="q-num">Question {{ $i + 1 }} of {{ $quiz->questions->count() }}</div>
        <div class="q-text">{{ $question->question_text }}</div>

        <div class="answers-list" data-qid="{{ $question->id }}">
            @foreach($question->answers as $answer)
            <label class="answer-option" id="opt-{{ $answer->id }}">
                <input type="radio"
                       name="q_{{ $question->id }}"
                       value="{{ $answer->id }}"
                       onchange="selectAnswer(this)">
                <div class="radio-dot"></div>
                <span class="answer-label">{{ $answer->answer_text }}</span>
            </label>
            @endforeach
        </div>

    </div>
    @endforeach

    <div class="quiz-footer">
        <span class="answered-count" id="answered-count">
            0 / {{ $quiz->questions->count() }} answered
        </span>
        <button type="submit" class="btn-submit">Submit Quiz →</button>
    </div>
</form>

<script src="{{ asset('js/quiz-show.js') }}"></script>

@if($quiz->time_limit)
<script>
    startTimer({{ $quiz->time_limit }});
</script>
@endif

@endsection