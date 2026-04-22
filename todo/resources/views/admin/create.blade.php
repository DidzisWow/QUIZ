@extends('layouts.app')
@section('title', 'Create Quiz — Admin')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')

<div class="admin-header">
    <div>
        <h1 class="admin-title">Create Quiz</h1>
        <p class="admin-sub">Fill in the details then add questions on the next step</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-ghost">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.quiz.store') }}">
        @csrf

        <div class="form-group">
            <label>Quiz Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   placeholder="e.g. World History" required>
            @error('title') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Difficulty</label>
                <select name="difficulty" required>
                    <option value="easy"   {{ old('difficulty') == 'easy'   ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard"   {{ old('difficulty') == 'hard'   ? 'selected' : '' }}>Hard</option>
                </select>
            </div>

            <div class="form-group">
                <label>Time Limit (minutes, 0 = none)</label>
                <input type="number" name="time_limit" value="{{ old('time_limit', 0) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"
                      placeholder="Brief description...">{{ old('description') }}</textarea>
        </div>

        <div class="form-group checkbox-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_published" value="1"
                       {{ old('is_published') ? 'checked' : '' }}>
                Publish immediately
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Create & Add Questions →</button>
    </form>
</div>

@endsection