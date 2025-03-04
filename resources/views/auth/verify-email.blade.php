@extends('layouts.auth')


@section('content')
<div class="card-body">
    <p class="login-box-msg">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>
        <div class="row">
            <div class="col-8">
                <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                    <button type="submit" class="btn btn-primary btn-block">Resend Verification Email</button>
                </form>
            </div>
            <div class="col-4">
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                    <button type="submit" class="btn btn-primary btn-block">logout</button>
                </form>
            </div>
        </div>
</div>
@endsection
