<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dolphin Healthtech</title>
    @include('components.auth.css')
    @include('components.auth.style')
</head>
<body>

<div class="login-container">
    <!-- LEFT SIDE -->
    <div class="login-left">
        <div class="login-left-content">
            <h1>Dolphin <span>Healthtech</span></h1>
            <small>Transforming Digital Healthcare</small>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="login-right">
        <div class="card p-4">
            @yield('content')
        </div>
    </div>
</div>

@include('components.auth.script')
</body>
</html>
