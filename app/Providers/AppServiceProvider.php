<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
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
            [
                'id' => 'dashboard',
                'label' => 'Dashboard',
                'icon' => 'layout-dashboard',
                'route' => 'admin.dashboard',
                'active_pattern' => 'dashboard',
            ],
            [
                'id' => 'inventory',
                'label' => 'Inventory',
                'icon' => 'briefcase-medical',
                'active_pattern' => 'inventory*',
                'route' => 'admin.medicine'
            ],
            [
                'id' => 'cashier',
                'label' => 'Cashier/POS',
                'icon' => 'credit-card',
                'active_pattern' => 'pos*',
                'route' => 'admin.cashier-menu'
            ],
            [
                'id' => 'users',
                'label' => 'User Management',
                'icon' => 'users',
                'route' => 'admin.users-data'
            ],
            [
                'id' => 'suppliers',
                'label' => 'Suppliers',
                'icon' => 'truck',
                'route' => 'admin.suppliers-data'
            ],
            [
                'id' => 'purchase-orders',
                'label' => 'Purchase Orders',
                'icon' => 'shopping-cart',
                'route' => 'admin.medicine-order'
            ],
            [
                'id' => 'activity',
                'label' => 'Activity Log',
                'icon' => 'shield',
                'route' => 'admin.activity-log'
            ],
            [
                'id' => 'reports',
                'label' => 'Reports',
                'icon' => 'file-text',
                'routes' => [
                    [
                        'id' => 'medicine-report',
                        'label' => 'Medicine Report',
                        'icon' => 'square-activity',
                        'route' => 'admin.reports.medicine-report',
                    ],
                    [
                        'id' => 'operational-report',
                        'label' => 'Operational Report',
                        'icon' => 'file-clock',
                        'route' => 'admin.reports.operational-report',
                    ],
                    [
                        'id' => 'financial-report',
                        'label' => 'Financial Report',
                        'icon' => 'receipt',
                        'route' => 'admin.reports.financial-report',
                    ],
                ]
            ],
        ];

        View::share('menuItems', $menuItems);

        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rp.' . number_format($amount, 2); ?>";
        });

        Paginator::useTailwind();

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}
