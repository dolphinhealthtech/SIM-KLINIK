<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'My-app'  }}</title>
        @include('components.dashboard.css')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        @php
            use App\Models\WebSetting;
            $settings = WebSetting::first();
        @endphp
        <div class="wrapper">
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}" alt="AdminLTELogo" height="60" width="60">
            </div>

            <!-- Navbar -->
            @include('components.dashboard.navbar')

            @include('components.dashboard.sidebar')

            @yield('content')

            @include('components.dashboard.footer')
            @include('components.dashboard.script')
        </div>
    </body>
</html>
