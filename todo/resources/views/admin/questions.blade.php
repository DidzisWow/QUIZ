@extends('layouts.app')
@section('title', 'Questions — ' . $quiz->title)

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')

<div class="admin-header">
    <div>
        <h1 class="admin-title">{{ $quiz->title }}</h1>
        <p class="admin-sub">{{ $quiz->questions->count() }} questions · {{ ucfirst($quiz->difficulty) }}
            · <span class="status-badge {{ $quiz->is_published ? 'published' : 'draft' }}">
                {{ $quiz->is_published ? 'Published' : 'Draft' }}
              </span>
        </p>
    </div>
    <div style="display:flex;gap:.6rem">
        <form method="POST" action="{{ route('admin.quiz.toggle', $quiz) }}">
            @csrf
            <button type="submit" class="btn {{ $quiz->is_published ? 'btn-ghost' : 'btn-primary' }}">
                {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
            </button>
        </form>
        <a href="{{ route('admin.index') }}" class="btn btn-ghost">← Back</a>
    </div>
</div>

<div class="admin-grid">

    {{-- Add question form --}}
    <div class="admin-card">
        <div class="card-head"><h2>Add Question</h2></div>

        <form method="POST" action="{{ route('admin.quiz.storeQuestion', $quiz) }}">
            @csrf

            <div class="form-group">
                <label>Question text</label>
                <textarea name="question_text" rows="3"
                          placeholder="Type your question here..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Type</label>
                    <select name="question_type" id="q-type" onchange="toggleAnswerMode(this.value)">
                        <option value="single">Single choice</option>
                        <option value="true_false">True / False</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Points</label>
                    <input type="number" name="points" value="1" min="1" max="10">
                </div>
            </div>

            {{-- Regular answers --}}
            <div id="answers-section">
                <label class="section-label">
                    Answers <span class="hint-text">(tick the correct one)</span>
                </label>
                @for($i = 0; $i < 4; $i++)
                <div class="answer-input-row">
                    <input type="checkbox" name="correct[]" value="{{ $i }}" class="correct-check">
                    <input type="text" name="answers[]"
                           placeholder="Answer {{ $i + 1 }}"
                           {{ $i < 2 ? 'required' : '' }}>
                </div>
                @endfor
            </div>

            {{-- True/False --}}
            <div id="tf-section" style="display:none">
                <label class="section-label">Correct answer</label>
                <div class="tf-row">
                    <label class="tf-option">
                        <input type="radio" name="correct[]" value="0"> True
                    </label>
                    <label class="tf-option">
                        <input type="radio" name="correct[]" value="1"> False
                    </label>
                </div>
                <input type="hidden" name="answers[]" value="True">
                <input type="hidden" name="answers[]" value="False">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top:1rem">
                Add Question
            </button>
        </form>
    </div>

    {{-- Existing questions --}}
    <div class="admin-card wide">
        <div class="card-head">
            <h2>Questions</h2>
            <span class="count-badge">{{ $quiz->questions->count() }}</span>
        </div>

        @if($quiz->questions->isEmpty())
            <p class="empty-msg">No questions yet. Add your first one!</p>
        @else
            @foreach($quiz->questions as $i => $question)
            <div class="question-row">
                <div class="question-row-top">
                    <span class="q-index">Q{{ $i + 1 }}</span>
                    <span class="q-type-tag">{{ $question->question_type }}</span>
                    <span class="q-pts">{{ $question->points }} pt</span>
                    <form method="POST"
                          action="{{ route('admin.question.delete', $question) }}"
                          style="margin-left:auto"
                          onsubmit="return confirm('Delete this question?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn btn-danger-sm">Delete</button>
                    </form>
                </div>
                <p class="q-text-preview">{{ $question->question_text }}</p>
                <div class="answers-preview">
                    @foreach($question->answers as $ans)
                    <span class="ans-chip {{ $ans->is_correct ? 'ans-correct' : '' }}">
                        {{ $ans->is_correct ? '✔' : '○' }} {{ $ans->answer_text }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endforeach
        @endif
    </div>

</div>

<script>
function toggleAnswerMode(type) {
    const regular = document.getElementById('answers-section');
    const tf      = document.getElementById('tf-section');
    if (type === 'true_false') {
        regular.style.display = 'none';
        tf.style.display      = 'block';
        regular.querySelectorAll('input[required]').forEach(i => i.removeAttribute('required'));
    } else {
        regular.style.display = 'block';
        tf.style.display      = 'none';
        regular.querySelectorAll('input[type="text"]').forEach((inp, idx) => {
            if (idx < 2) inp.setAttribute('required', '');
        });
    }
}
</script>

@endsection