<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-teal elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <img src="{{ asset('setting/' . ($settings->profile_image ?? 'default.jpg')) }}"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <strong class="brand-text font-weight-light" style="color: #6c757d;">
            {{ $settings->nama ?? '' }}
        </strong>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- SidebarSearch Form -->
        <div class="form-inline mt-3">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar"
                       type="search"
                       placeholder="Search"
                       aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        @php
            use App\Models\WebSetting;

            $setting = WebSetting::first();
            // Toggle Gudang Utama dari DB
            $isGudangUtamaActive = $setting->is_gudangutama_active ?? true;
        @endphp

        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-compact nav-child-indent nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false">

                @foreach ($menus as $menu)
                    @php
                        $isActive = request()->is(trim($menu->url, '/')) ||
                                    $menu->children->where(fn($child) =>
                                        request()->is(trim($child->url, '/')) ||
                                        $child->children->where(fn($grandchild) =>
                                            request()->is(trim($grandchild->url, '/'))
                                        )->isNotEmpty()
                                    )->isNotEmpty();
                    @endphp

                    <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
                        <a href="{{ $menu->url ? url($menu->url) : '#' }}"
                           class="nav-link {{ $isActive ? 'active' : '' }}">
                            <i class="nav-icon fa fa-{{ $menu->icon }}"
                               {{ $isActive ? 'style=color:#ffffff;' : 'style=color:#6c757d;' }}>
                            </i>
                            <p {{ $isActive ? 'style=color:#ffffff;' : 'style=color:#6c757d;' }}>
                                {{ $menu->name }}
                                @if ($menu->children->isNotEmpty())
                                    <i class="right fa fa-angle-left"></i>
                                @endif
                            </p>
                        </a>

                        @if ($menu->children->isNotEmpty())
                            <ul class="nav nav-treeview">
                                @foreach ($menu->children as $child)
                                    @php
                                        $isChildActive = request()->is(trim($child->url, '/')) ||
                                                         $child->children->where(fn($grandchild) =>
                                                             request()->is(trim($grandchild->url, '/'))
                                                         )->isNotEmpty();

                                        // Tentukan apakah child ini harus di-hide
                                        $hideThisMenu =
                                            !$isGudangUtamaActive &&
                                            (
                                                strtolower($child->name) === 'gudang utama (obat)' ||
                                                trim($child->url, '/') === 'data-master-gudang/gudang-utama'
                                            );
                                    @endphp

                                    @if (!$hideThisMenu)
                                        <li class="nav-item {{ $isChildActive ? 'menu-open' : '' }}">
                                            <a href="{{ $child->url ? url($child->url) : '#' }}"
                                               class="nav-link {{ request()->is(trim($child->url, '/')) ? 'active' : '' }}">
                                                <i class="fa fa-{{ $child->icon }} nav-icon"
                                                   {{ $isChildActive ? 'style=color:#ffffff;' : 'style=color:#6c757d;' }}>
                                                </i>
                                                <p {{ $isChildActive ? 'style=color:#ffffff;' : 'style=color:#6c757d;' }}>
                                                    {{ $child->name }}
                                                    @if ($child->children->isNotEmpty())
                                                        <i class="right fa fa-angle-left"></i>
                                                    @endif
                                                </p>
                                            </a>

                                            @if ($child->children->isNotEmpty())
                                                <ul class="nav nav-treeview">
                                                    @foreach ($child->children as $grandchild)
                                                        <li class="nav-item">
                                                            <a href="{{ url($grandchild->url) }}"
                                                               class="nav-link {{ request()->is(trim($grandchild->url, '/')) ? 'active' : '' }}">
                                                                <i class="fa fa-{{ $grandchild->icon }} nav-icon"
                                                                   {{ request()->is(trim($grandchild->url, '/')) ? 'style=color:#ffffff;' : 'style=color:#6c757d;' }}>
                                                                </i>
                                                                <p {{ request()->is(trim($grandchild->url, '/')) ? 'style=color:#ffffff;' : 'style=color:#6c757d;' }}>
                                                                    {{ $grandchild->name }}
                                                                </p>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach

                <li>
                    <br><br><br>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
