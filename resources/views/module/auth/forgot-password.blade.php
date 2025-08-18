@extends('layouts.auth')


@section('content')
<div class="card-body">
    @if (session('status') === 'verification-link-sent')
        <div class="alert alert-success mb-4 text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
    <form method="POST" action="{{ route('password.email') }}">
    @csrf
        <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">Request new password</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
    <p class="mt-3 mb-1">
      <a href="{{ route('login') }}">Login</a>
    </p>
</div>
@endsection
