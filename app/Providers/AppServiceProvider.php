<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $menuItems = [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'dashboard'],
            ['id' => 'inventory', 'label' => 'Inventory', 'icon' => 'package', 'route' => 'inventory'],
            ['id' => 'cashier', 'label' => 'Cashier/POS', 'icon' => 'credit-card', 'route' => 'cashier'],
            ['id' => 'users', 'label' => 'User Management', 'icon' => 'users', 'route' => 'users'],
            ['id' => 'suppliers', 'label' => 'Suppliers', 'icon' => 'truck', 'route' => 'suppliers'],
            ['id' => 'purchase-orders', 'label' => 'Purchase Orders', 'icon' => 'shopping-cart', 'route' => 'purchase-orders'],
            ['id' => 'transactions', 'label' => 'Transaction Summary', 'icon' => 'receipt', 'route' => 'transactions'],
            ['id' => 'audit', 'label' => 'Audit Log', 'icon' => 'shield', 'route' => 'audit'],
            ['id' => 'reports', 'label' => 'Reports', 'icon' => 'file-text', 'route' => 'reports'],
        ];

        View::share('menuItems', $menuItems);
    }
}
