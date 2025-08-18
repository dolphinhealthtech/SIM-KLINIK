@extends('layouts.auth')

@section('content')
<div class="card-body px-3 py-4">
    <p class="text-center text-muted mb-4">Masuk ke akun Anda</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="login">Email atau Username</label>
            <div class="input-group">
                <input type="text" id="login" name="login" class="form-control" placeholder="Email atau Username" value="{{ old('login') }}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
            </div>
            @error('login')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">Password</label>
            <div class="input-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
            </div>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
            <label class="form-check-label" for="remember_me">Ingat Saya</label>
        </div>

        <button type="submit" class="btn btn-primary btn-block mb-3">Masuk</button>

        <div class="text-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Lupa Password?</a>
            @endif
        </div>
    </form>
</div>
@endsection
