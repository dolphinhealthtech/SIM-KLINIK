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
            background-color: #021f34;
            background-image: linear-gradient(135deg, #021f34, #04364f);
            overflow-x: hidden;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }

        .login-left {
            flex: 1;
            background: url('/dist/img/logo2.png') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;   /* Center horizontally */
            align-items: center;       /* Center vertically */
        }

        .login-left::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.3), rgba(2, 31, 52, 0.8));
            z-index: 0;
        }

        .login-left-content {
            position: relative;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.15);
            color: white;
            z-index: 1;
            text-align: center;
            margin-top: 415px;
        }

        .login-left h1 {
            font-size: 2.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-left span {
            color: #00ffc3;
        }

        .login-left small {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 30px;
            background: radial-gradient(circle at top left, #04293a, #021f34);
        }

        .card {
            width: 100%;
            max-width: 420px;
            border-radius: 1.25rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 40px rgba(0, 255, 195, 0.2);
            padding: 30px;            
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 45px rgba(0, 255, 195, 0.3);
        }

        .form-control {
            border: 1px solid #ced4da;
            background-color: #f8f9fa;
            color: #212529;
        }

        .form-control:focus {
            border-color: #00ffc3;
            box-shadow: 0 0 0 0.2rem rgba(0, 255, 195, 0.25);
        }

        .btn-primary {
            background-color: #00ffc3;
            border: none;
            color: #000;
        }

        .btn-primary:hover {
            background-color: #00d5a7;
        }

        .text-muted, .form-check-label {
            color: #444;
        }

        .card a {
            color: #007bff;
        }

        .card a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-left, .login-right {
                flex: none;
                width: 100%;
                min-height: 50vh;
            }

            .login-left-content {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- LEFT -->
    <div class="login-left">
        <div class="login-left-content">
            <h1>Dolphin <span>Healthtech</span></h1>
            <small>Transforming Digital Healthcare</small>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-right">
        <div class="card">
            @yield('content')
        </div>
    </div>
</div>

@include('components.auth.script')
</body>
</html>
