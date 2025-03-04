<!DOCTYPE html>
<html lang="id">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Log in (v2)</title>
        @include('components.auth.css')
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
        <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{ asset('index2.html') }}" class="h1"><b>Admin</b>LTE</a>
                </div>
                @yield('content')
                <!-- /.card-body -->
            </div>
        <!-- /.card -->
        </div>
    <!-- /.login-box -->
        @include('components.auth.script')
    </body>
</html>
