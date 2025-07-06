<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'My-app' }}</title>
    @include('components.monitor.css')
</head>

<body class="hold-transition layout-top-nav">
    @php
        use App\Models\WebSetting;
        $settings = WebSetting::first();
    @endphp
    <div class="wrapper">

        <!-- Navbar -->
        @include('components.monitor.navbar')


        @yield('content')

        @include('components.monitor.footer')
        @include('components.monitor.script')
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
