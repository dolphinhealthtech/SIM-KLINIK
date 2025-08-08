<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'My-app' }}</title>
    @include('components.dashboard.css')
</head>

<body class=" layout-fixed">
    @php
        use App\Models\WebSetting;
        $settings = WebSetting::first();
    @endphp
    <div class="wrapper">

        <!-- Navbar -->
        @include('components.dashboard.navbar')

        @include('components.dashboard.sidebar')

        @yield('content')

        @include('components.dashboard.footer')
        @include('components.dashboard.script')
        @if (session('berhasil'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('berhasil')) !!}, // Mencegah error JavaScript
                    showConfirmButton: true
                });
            </script>
        @endif

        @if (session('gagal'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: {!! json_encode(session('gagal')) !!},
                    showConfirmButton: true
                });
            </script>
        @endif

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!},
                    showConfirmButton: true
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: {!! json_encode(session('error')) !!},
                    showConfirmButton: true
                });
            </script>
        @endif

    </div>
</body>

</html>
