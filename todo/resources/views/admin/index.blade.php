@extends('layouts.app')
@section('title', 'Admin — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')

<div class="admin-header">
    <div>
        <h1 class="admin-title">⚙ Admin Panel</h1>
        <p class="admin-sub">Manage quizzes, questions and categories</p>
    </div>
    <a href="{{ route('admin.quiz.create') }}" class="btn btn-primary">+ New Quiz</a>
</div>

<div class="admin-grid">

    {{-- Quizzes table --}}
    <div class="admin-card wide">
        <div class="card-head">
            <h2>All Quizzes</h2>
            <span class="count-badge">{{ $quizzes->count() }}</span>
        </div>

        @if($quizzes->isEmpty())
            <p class="empty-msg">No quizzes yet. Create one!</p>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Difficulty</th>
                    <th>Questions</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizzes as $quiz)
                <tr>
                    <td class="td-title">{{ $quiz->title }}</td>
                    <td>{{ $quiz->category->name }}</td>
                    <td><span class="diff-badge diff-{{ $quiz->difficulty }}">{{ $quiz->difficulty }}</span></td>
                    <td>{{ $quiz->questions->count() }}</td>
                    <td>
                        <span class="status-badge {{ $quiz->is_published ? 'published' : 'draft' }}">
                            {{ $quiz->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="td-actions">
                        <a href="{{ route('admin.quiz.questions', $quiz) }}" class="action-btn">Questions</a>

                        <form method="POST" action="{{ route('admin.quiz.toggle', $quiz) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="action-btn {{ $quiz->is_published ? 'btn-warn' : 'btn-success' }}">
                                {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.quiz.delete', $quiz) }}" style="display:inline"
                              onsubmit="return confirm('Delete this quiz and all its questions?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn btn-danger-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Add category --}}
    <div class="admin-card">
        <div class="card-head"><h2>Add Category</h2></div>

        <form method="POST" action="{{ route('admin.category.store') }}">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" placeholder="e.g. Science" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Icon (emoji)</label>
                    <input type="text" name="icon" placeholder="🔬" maxlength="5">
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <input type="color" name="color" value="#6366f1" class="color-input">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>

        @if($categories->count())
        <div class="cat-list">
            @foreach($categories as $cat)
            <span class="cat-pill" style="border-color: {{ $cat->color }}">
                {{ $cat->icon }} {{ $cat->name }}
            </span>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection