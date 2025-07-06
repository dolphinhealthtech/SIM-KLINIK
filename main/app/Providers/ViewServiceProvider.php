<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\menu;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.dashboard.sidebar', function ($view) {
            if (Auth::check()) {
                $roleIds = Auth::user()->roles->pluck('id');

                // Ambil menu utama berdasarkan role
                $menus = Menu::whereNull('parent_id')
                    ->whereHas('roles', function ($query) use ($roleIds) {
                        $query->whereIn('roles.id', $roleIds);
                    })
                    ->with(['children' => function ($query) use ($roleIds) {
                        // Ambil submenu yang sesuai role user
                        $query->whereHas('roles', function ($q) use ($roleIds) {
                            $q->whereIn('roles.id', $roleIds);
                        })->with(['children' => function ($q) use ($roleIds) {
                            // Ambil sub-submenu yang sesuai role user
                            $q->whereHas('roles', function ($qq) use ($roleIds) {
                                $qq->whereIn('roles.id', $roleIds);
                            });
                        }]);
                    }])
                    ->orderBy('order', 'asc')
                    ->get();

                $view->with('menus', $menus);
            } else {
                $view->with('menus', collect([]));
            }
        });
    }
}

