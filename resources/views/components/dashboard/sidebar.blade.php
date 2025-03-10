 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $settings->nama ?? '' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- SidebarSearch Form -->
        <div class="form-inline mt-3">
            <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach ($menus as $menu)
                    @php
                        // Cek apakah menu aktif berdasarkan URL
                        $isActive = request()->is(trim($menu->url, '/')) || $menu->children->where(fn($child) => request()->is(trim($child->url, '/')))->isNotEmpty();
                    @endphp

                    <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
                        <a href="{{ $menu->url ? url($menu->url) : '#' }}" class="nav-link {{ request()->is(trim($menu->url, '/')) ? 'active' : '' }}">
                            <i class="nav-icon fa fa-{{ $menu->icon }}"></i>
                            <p>
                                {{ $menu->name }}
                                @if ($menu->children->isNotEmpty())
                                    <i class="right fa fa-angle-left"></i>
                                @endif
                            </p>
                        </a>

                        @if ($menu->children->isNotEmpty())
                            <ul class="nav nav-treeview">
                                @foreach ($menu->children as $child)
                                    <li class="nav-item">
                                        <a href="{{ url($child->url) }}" class="nav-link {{ request()->is(trim($child->url, '/')) ? 'active' : '' }}">
                                            <i class="fa fa-{{ $child->icon }} nav-icon"></i>
                                            <p>{{ $child->name }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach




            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                {{-- <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-regular fa-circle-user"></i>
                        <p>
                            Role & Permission
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('role.get') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Role
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permission.get') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Permission
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.get') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                    </ul>
                </li> --}}
            </ul>
        </nav>
    <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
