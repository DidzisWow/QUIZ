@extends('layouts.app')
@section('title', 'Create Account — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-wrap">
    <div class="auth-card glow-left">

        <h1 class="auth-title">Izveido kontu</h1>
        <p class="auth-sub">Pievinojies Quiz un sāc mācīties</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                       placeholder="Jane Smith"
                       autocomplete="name"
                       autofocus>
                @error('name')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }} focus-cyan"
                       placeholder="you@example.com"
                       autocomplete="email">
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }} focus-cyan"
                       placeholder="Min. 8 characters"
                       autocomplete="new-password">
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
                <span class="hint">Minimum 8 characters</span>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="focus-cyan"
                       placeholder="Repeat password"
                       autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-cyan btn-full" style="margin-top:.5rem">
                Create account →
            </button>
        </form>

        <p class="auth-switch">
            Already have an account?
            <a href="{{ route('login') }}" class="link-cyan">Log in</a>
        </p>

    </div>
</div>
@endsection





