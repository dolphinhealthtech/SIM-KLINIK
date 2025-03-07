<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <div class="container">
        <a class="navbar-brand">
            <img src="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{ $settings->nama ?? '' }}</span>
        </a>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>

        </ul>
    </div>
</nav>
