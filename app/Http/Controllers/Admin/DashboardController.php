<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's statistics
        $today = Carbon::today();
        
        $summary = [
            'todayOrders' => Order::whereDate('created_at', $today)->count(),
            'ongoingOrders' => Order::whereIn('status', ['booked', 'assigned_to_driver', 'in_progress_pickup', 'in_progress_deliver'])->count(),
            'availableDrivers' => Driver::where('status', 'available')->count(),
            'todayRevenue' => Order::whereDate('created_at', $today)->sum('total_bill')
        ];

        // Get order trend for last 7 days
        $orderTrend = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i);
            $count = Order::whereDate('created_at', $date)->count();
            $orderTrend[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $count
            ];
        }

        // Get recent orders
        $recentOrders = Order::with(['user', 'driver', 'ambulanceType', 'purpose'])
            ->whereIn('status', ['created', 'booked'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get driver status
        $driverStatus = [
            'available' => Driver::where('status', 'available')->count(),
            'onDuty' => Driver::where('status', 'on_duty')->count(),
            'unavailable' => Driver::where('status', 'unavailable')->count()
        ];

        $title = 'Dashboard';

        return view('admin.dashboard', compact('summary', 'orderTrend', 'recentOrders', 'driverStatus', 'title'));
    }
}
