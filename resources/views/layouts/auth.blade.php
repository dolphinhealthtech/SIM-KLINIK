<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dolphin Healthtech</title>
    @include('components.auth.css')
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-container {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }
        .login-left {
            flex: 1;
            background-image: url('/dist/img/logo2.png');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
        }
        .login-left-content {
            background-color: rgba(0,0,0,0.5);
            padding: 40px;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgb(255, 255, 255);
        }
        .login-left h1 {
            font-size: 3rem;
            font-weight: 800;
            color: white;
        }
        .login-left span {
            color: #00ffc3;
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
            background: linear-gradient(135deg, #021f34, #04364f);
        }

        .card {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            background-color: rgba(0, 0, 0, 0.6);
            box-shadow: 0 0 25px rgba(0, 255, 195, 0.4);
            backdrop-filter: blur(6px);
            color: white;
            transition: all 0.4s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0 35px rgba(0, 255, 195, 0.6);
        }

        .card .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
        }

        .card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .card label,
        .card .form-check-label {
            color: #ffffff;
        }

        .card a {
            color: #00ffc3;
        }

        .card a:hover {
            text-decoration: underline;
        }

        .btn-primary {
            background-color: #00ffc3;
            border: none;
            color: #000;
        }

        .btn-primary:hover {
            background-color: #00d5a7;
        }
    </style>
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
