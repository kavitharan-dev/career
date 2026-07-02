@extends('layouts.arivexa')
@section('title', 'Login')
@section('content')
<section class="center-page">
    <div class="box" style="max-width: 400px; margin: 0 auto;">
        <h1 class="page-title" style="font-size: 22px;">Login</h1>
        <p class="text-muted">Sign in to continue your career path and roadmap.</p>

        @if (session('success'))
            <div class="msg msg-success" style="margin-top: 16px;">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="msg msg-error" style="margin-top: 16px;">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="msg msg-error" style="margin-top: 16px;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0 0 4px;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" style="margin-top: 20px;">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-control">
            </div>
            <label style="font-size: 14px; display: flex; gap: 8px; align-items: center; margin-bottom: 16px;">
                <input type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="btn btn-blue btn-block">Login</button>
        </form>
        <p class="text-muted" style="text-align: center; margin-top: 16px;">
            No account? <a href="{{ route('register') }}">Register</a>
        </p>
    </div>
</section>
@endsection
