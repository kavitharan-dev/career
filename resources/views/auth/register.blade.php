@extends('layouts.arivexa')
@section('title', 'Register')
@section('content')
<section class="center-page">
    <div class="box" style="max-width: 420px; margin: 0 auto;">
        <h1 class="page-title" style="font-size: 22px;">Register</h1>
        <p class="text-muted">Create your student account</p>

        @if ($errors->any())
            <div class="msg msg-error" style="margin-top: 16px;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0 0 4px;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" style="margin-top: 20px;">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Full name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-control" pattern="^[^\d]+$" title="Name cannot contain numbers">
                <p class="text-muted" style="margin-top: 4px;">Letters only — numbers not allowed</p>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="phone">Sri Lankan mobile</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="0771234567" class="form-control">
                <p class="text-muted" style="margin-top: 4px;">Format: 07XXXXXXXX</p>
                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-control">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
            </div>
            <button type="submit" class="btn btn-blue btn-block">Register</button>
        </form>
        <p class="text-muted" style="text-align: center; margin-top: 16px;">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
</section>
@endsection
