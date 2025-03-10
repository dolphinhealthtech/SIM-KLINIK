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
            $role = Auth::user()->getRoleNames();
            $menus = Menu::whereNull('parent_id') // Ambil hanya menu utama
                ->with('children') // Pastikan submenu diambil
                ->orderBy('order', 'asc')
                ->get();
            $view->with('menus', $menus);
        });
    }
}
