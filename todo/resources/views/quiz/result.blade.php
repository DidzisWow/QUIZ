@extends('layouts.app')
@section('title', 'Result — Quiz')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/result.css') }}">
@endsection

@section('content')

@php
    $pct   = $attempt->max_score > 0
               ? round(($attempt->score / $attempt->max_score) * 100)
               : 0;
    $grade = $pct >= 80 ? ' Excellent!'
           : ($pct >= 60 ? ' Good job!'
           : ' Keep practicing!');
@endphp

{{-- Score hero --}}
<div class="result-hero">
    <div class="score-ring" style="--pct: {{ $pct }}">
        <span class="score-num">{{ $pct }}%</span>
    </div>
    <h1 class="result-title">{{ $grade }}</h1>
    <p class="result-sub">Punkti iegūti {{ $attempt->score }} no {{ $attempt->max_score }} points</p>

    <div class="result-badges">
        <div class="r-badge">
            <strong>{{ $attempt->score }}</strong>
            Punkti dabūti
        </div>
        <div class="r-badge">
            <strong>{{ $attempt->userAnswers->where('is_correct', true)->count() }}</strong>
            Pareizi
        </div>
        <div class="r-badge">
            <strong>{{ $attempt->userAnswers->where('is_correct', false)->count() }}</strong>
            Nepareizi
        </div>
    </div>
</div>

{{-- Answer review --}}
<div class="review-section">
    <div class="review-header">
        <p class="review-title">Pārbaudi atbildes</p>
        <div class="review-filters">
            <button class="filter-btn active" onclick="filterReview('all', this)">Visi</button>
            <button class="filter-btn" onclick="filterReview('correct', this)">✔ Pareizi</button>
            <button class="filter-btn" onclick="filterReview('incorrect', this)">✘ Nepareizi</button>
        </div>
    </div>

    @foreach($attempt->userAnswers as $i => $ua)
    @php
        /* Find the correct answer for this question */
        $correctAnswer = $ua->question->answers->firstWhere('is_correct', true);
    @endphp
    <div class="review-item {{ $ua->is_correct ? 'correct' : 'incorrect' }}"
         data-result="{{ $ua->is_correct ? 'correct' : 'incorrect' }}">

        <div class="review-top">
            <span class="review-num">Q{{ $i + 1 }}</span>
            <span class="review-badge {{ $ua->is_correct ? 'badge-correct' : 'badge-wrong' }}">
                {{ $ua->is_correct ? '✔ Nepareizi' : '✘ Pareizi' }}
            </span>
        </div>

        <div class="review-q">{{ $ua->question->question_text }}</div>

        <div class="review-answers">
            {{-- User's answer --}}
            <div class="review-ans {{ $ua->is_correct ? 'ans-correct' : 'ans-wrong' }}">
                <span class="ans-label">Tava atbilde</span>
                <span class="ans-text">{{ $ua->answer->answer_text }}</span>
            </div>

            {{-- Show correct answer only if they got it wrong --}}
            @if(!$ua->is_correct && $correctAnswer)
            <div class="review-ans ans-correct">
                <span class="ans-label">Pareiza atbilde</span>
                <span class="ans-text">{{ $correctAnswer->answer_text }}</span>
            </div>
            @endif
        </div>

    </div>
    @endforeach
</div>

<div class="cta-row">
    <a href="{{ route('quiz.show', $attempt->quiz_id) }}" class="btn btn-ghost">Pārmeiģini</a>
    <a href="{{ route('quiz.index') }}" class="btn btn-primary">Visi quiz →</a>
</div>

<script>
function filterReview(type, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    document.querySelectorAll('.review-item').forEach(item => {
        if (type === 'all' || item.dataset.result === type) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

@endsection