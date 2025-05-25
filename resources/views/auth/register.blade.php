@extends('layouts.auth')


@section('content')
<div class="card-body">
    <p class="login-box-msg">Register a new membership</p>

    <form method="POST" action="{{ route('register') }}">
    @csrf
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Full name" value="{{ old('name') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="text" id="username" name="username" class="form-control" placeholder="username" value="{{ old('username') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('username')
                    <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
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
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
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
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Retype password">
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
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="agreeTerms" name="agreeTerms" value="agree">
                    <label for="agreeTerms">
                        I agree to the <a href="#">terms</a>
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

    {{-- <div class="social-auth-links text-center">
      <a href="#" class="btn btn-block btn-primary">
        <i class="fab fa-facebook mr-2"></i>
        Sign up using Facebook
      </a>
      <a href="#" class="btn btn-block btn-danger">
        <i class="fab fa-google-plus mr-2"></i>
        Sign up using Google+
      </a>
    </div> --}}

    <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
</div>
@endsection
