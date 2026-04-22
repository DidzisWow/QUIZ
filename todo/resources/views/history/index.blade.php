@extends('layouts.app')
@section('title', 'My History — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
@endsection

@section('content')

<div class="page-header">
    <h1>My <em>History</em></h1>
    <p>All your past quiz attempts in one place.</p>
</div>

{{-- Stats row --}}
<div class="history-stats">
    <div class="h-stat">
        <div class="h-stat-num">{{ $totalAttempts }}</div>
        <div class="h-stat-lbl">Total attempts</div>
    </div>
    <div class="h-stat">
        <div class="h-stat-num">{{ $summary->count() }}</div>
        <div class="h-stat-lbl">Quizzes played</div>
    </div>
    <div class="h-stat">
        <div class="h-stat-num">{{ $avgScore ? round($avgScore) . '%' : 'N/A' }}</div>
        <div class="h-stat-lbl">Average score</div>
    </div>
</div>

@if($summary->isEmpty())
    <div class="empty-history">
        <div class="empty-icon">📭</div>
        <p>You haven't taken any quizzes yet.</p>
        <a href="{{ route('quiz.index') }}" class="btn btn-primary">Browse Quizzes →</a>
    </div>
@else
    <div class="history-list">
        @foreach($summary as $item)
        <div class="history-card">
            <div class="history-card-top">
                <div class="history-card-left">
                    <div class="h-cat-icon">{{ $item['quiz']->category->icon ?? '📝' }}</div>
                    <div>
                        <div class="h-quiz-title">{{ $item['quiz']->title }}</div>
                        <div class="h-quiz-meta">
                            <span>{{ $item['quiz']->category->name }}</span>
                            <span>·</span>
                            <span class="diff-badge diff-{{ $item['quiz']->difficulty }}">{{ $item['quiz']->difficulty }}</span>
                            <span>·</span>
                            <span>Last played {{ $item['last_played']->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                <div class="history-card-right">
                    <div class="h-score-ring" style="--pct: {{ $item['best_pct'] }}">
                        <span class="h-score-num">{{ $item['best_pct'] }}%</span>
                    </div>
                    <div class="h-score-label">Best score</div>
                </div>
            </div>

            <div class="history-card-bottom">
                <div class="h-times">
                    🔁 Played <strong>{{ $item['times_taken'] }}</strong> {{ $item['times_taken'] === 1 ? 'time' : 'times' }}
                </div>

                {{-- Attempt history --}}
                <div class="attempt-list">
                    @foreach($item['attempts'] as $attempt)
                    @php
                        $pct = $attempt->max_score > 0
                            ? round(($attempt->score / $attempt->max_score) * 100)
                            : 0;
                    @endphp
                    <a href="{{ route('quiz.result', $attempt->id) }}" class="attempt-chip">
                        <span class="attempt-pct {{ $pct >= 80 ? 'pct-good' : ($pct >= 60 ? 'pct-mid' : 'pct-low') }}">
                            {{ $pct }}%
                        </span>
                        <span class="attempt-date">{{ $attempt->created_at->format('d M') }}</span>
                    </a>
                    @endforeach
                </div>

                <a href="{{ route('quiz.show', $item['quiz']->id) }}" class="btn btn-primary btn-sm">
                    Play again →
                </a>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection