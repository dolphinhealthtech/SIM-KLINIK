<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>dolphinhealthtech</title>
    @include('components.auth.css')
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #007bff, #00ffc3);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }
        .login-left h1 {
            font-size: 3rem;
            font-weight: 800;
        }
        .login-left small {
            font-size: 1rem;
            color: rgba(255,255,255,0.9);
        }
        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #f8f9fa;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- LEFT SIDE -->
    <div class="login-left" style="background-image: url('/dist/img/login.jpeg'); background-size: cover; background-position: center;">
        <div style="background-color: rgba(0,0,0,0.5); padding: 40px; border-radius: 1rem;">
            <h1>Dolphin <span style="color: #00ffc3;">Healthtech</span></h1>
            <small>Transforming Digital Healthcare</small>
        </div>
    </div>


    <!-- RIGHT SIDE -->
    <div class="login-right">
        <div class="card p-3 bg-white">
            @yield('content')
        </div>
    </div>
</div>

@include('components.auth.script')
</body>
</html>
