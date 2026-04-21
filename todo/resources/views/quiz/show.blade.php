@extends('layouts.app')
@section('title', $quiz->title . ' — Quiz')

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
            <span class="meta-pill"> {{ $quiz->questions->count() }} questions</span>
            <span class="meta-pill"> {{ $quiz->category->name }}</span>
            <span class="meta-pill diff-{{ $quiz->difficulty }}">{{ ucfirst($quiz->difficulty) }}</span>
        </div>
    </div>

    @if($quiz->time_limit)
        <div id="timer-box">
            <div class="t-label">Laiks atlicis</div>
            <div id="timer-display">--:--</div>
        </div>
    @endif
</div>

{{-- Progress bar --}}
<div class="progress-bar">
    <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
</div>
<div class="progress-label">
    <span id="q-counter">1 / {{ $quiz->questions->count() }}</span>
</div>

<form method="POST" action="{{ route('quiz.submit', $quiz) }}" id="quiz-form">
    @csrf

    @foreach($quiz->questions as $i => $question)
    <div class="question-block {{ $i === 0 ? 'active' : '' }}"
         data-index="{{ $i }}">

        <div class="q-num">Question {{ $i + 1 }} of {{ $quiz->questions->count() }}</div>
        <div class="q-text">{{ $question->question_text }}</div>

        <div class="answers-list">
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

        {{-- Navigation buttons --}}
        <div class="q-nav">
            @if($i > 0)
                <button type="button" class="btn-nav btn-prev" onclick="goTo({{ $i - 1 }})">← Prev</button>
            @else
                <span></span>
            @endif

            @if($i < $quiz->questions->count() - 1)
                <button type="button" class="btn-nav btn-next" onclick="goTo({{ $i + 1 }})">Next →</button>
            @else
                <button type="submit" class="btn-submit">Iesūti quiz →</button>
            @endif
        </div>
    </div>
    @endforeach

</form>

<script>
const total = {{ $quiz->questions->count() }};
let current = 0;

function goTo(index) {
    document.querySelectorAll('.question-block').forEach((b, i) => {
        b.classList.toggle('active', i === index);
    });
    current = index;
    document.getElementById('q-counter').textContent = `${index + 1} / ${total}`;
    document.getElementById('progress-fill').style.width = `${((index + 1) / total) * 100}%`;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function selectAnswer(input) {
    document.querySelectorAll(`[name="${input.name}"]`).forEach(r => {
        r.closest('.answer-option').classList.remove('selected');
    });
    input.closest('.answer-option').classList.add('selected');
}

@if($quiz->time_limit)
let seconds = {{ $quiz->time_limit }};
const display = document.getElementById('timer-display');
function tick() {
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    display.textContent = `${m}:${s}`;
    if (seconds <= 30) display.classList.add('warn');
    if (seconds <= 0) { document.getElementById('quiz-form').submit(); return; }
    seconds--;
    setTimeout(tick, 1000);
}
tick();
@endif
</script>

@endsection