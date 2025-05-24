<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '5s';

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', $this->getUsersCount())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Newly Users', $this->getNewUsersToday())
                ->description('Users registered today')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),

            Stat::make('Verified Users', $this->getVerifiedUsers())
                ->description('User with verified email')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('Total Orders', $this->getOrdersCount())
                ->description('All orders in system')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Pending Orders', $this->getPendingOrdersCount())
                ->description('Orders with pending status')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Monthly Revenue', '$' . number_format($this->getMonthlyRevenue()))
                ->description('Revenue this month')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }

    private function getUsersCount(): int
    {
        return User::count();
    }

    private function getNewUsersToday(): int
    {
        return User::whereDate('created_at', today())->count();
    }

    private function getVerifiedUsers(): int
    {
        return User::where('email_verified_at', '!=', null)->count();
    }

    private function getOrdersCount(): int
    {
        return Order::count();
    }

    private function getPendingOrdersCount(): int
    {
        return Order::where('status', 'pending')->count();
    }

    private function getMonthlyRevenue(): float
    {
        return Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
    }
}
