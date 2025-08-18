@extends('layouts.auth')


@section('content')

<div class="card-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form method="POST" action="{{ route('password.store') }}">
    @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="input-group mb-3">
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" readonly>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" class="form-control" id="password" name="password" equired autocomplete="current-password" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                    <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="input-group mb-3">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" equired autocomplete="new-password_confirmation" placeholder="Confirm Password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password_confirmation')
                    <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <!-- /.col -->
            <div class="ms-auto">
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
</div>
@endsection
