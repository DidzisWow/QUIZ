@extends('layouts.app')
@section('title', 'Log In — QuizForge')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="auth-wrap">
    <div class="auth-card glow-right">

        <h1 class="auth-title">Sveicināti atpakaļ</h1>
        <p class="auth-sub">Log in un turpini savu quiz pildišanu</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="you@example.com"
                       autocomplete="email"
                       autofocus>
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="••••••••"
                       autocomplete="current-password">
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                Log in →
            </button>
        </form>

        <p class="auth-switch">
            Nav konts?
            <a href="{{ route('register') }}" class="link-purple">Izveido kontu</a>
        </p>

    </div>
</div>
@endsection