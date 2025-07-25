<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Driver;
use Carbon\Carbon;
use App\Services\FCMNotificationService;
use App\Models\FCMTokens; // Import model for FCM tokens if needed

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:read-dashboard'], ['only' => ['index', 'show']]);
    }

    public function index()
    {
        // Get today's statistics
        $today = Carbon::today();
        
        $summary = [
            'todayOrders' => Order::whereDate('created_at', $today)->count(),
            'ongoingOrders' => Order::whereIn('order_status', ['booked', 'assigned_to_driver', 'in_progress_pickup', 'in_progress_deliver'])->count(),
            'availableDrivers' => Driver::isAvailable()->count(),
            'todayRevenue' => Order::whereDate('created_at', $today)->sum('total_bill')
        ];

        // Get order trend for last 7 days
        $orderTrend = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i);
            $count = Order::whereDate('created_at', $date)->count();
            $orderTrend['data'][] = $count;
            $orderTrend['categories'][] = $date->format('d F');
        }
        // Get recent orders
        $recentOrders = Order::with(['user', 'driver', 'ambulanceType', 'purpose'])
            ->whereIn('order_status', ['created', 'booked'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get driver status
        $driverStatus = [
            'available' => Driver::isAvailable()->count(),
            'on_duty' => Driver::isOnDuty()->count(),
            'unavailable' => Driver::isUnavailable()->count()
        ];

        $title = 'Dashboard';

        return view('admin.dashboard', compact('summary', 'orderTrend', 'recentOrders', 'driverStatus', 'title'));
    }

    public function send_notif(FCMNotificationService $fcm)
    {
        $fcm->send(
            'e7dXawn_6-Xi1yjYtKGKVL:APA91bG9gjAVsGhTH68kygfDOvErdKwyLs-53sjJUGNt8npL0-qdbC_42aw3NTeUmUfhZIxHpz5fXZaexUF4XRdP4tlz7WZ73AueHZd4KmUVsg6VMSclKYI', 
            'Mari Kita Crot', 
            'Crot sana crot sini crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini',
            ['orderId' => "42"]
        );

        $tokens = FcmTokens::pluck('token')->toArray();
        if (empty($tokens)) {
            return response()->json(['message' => 'No active FCM tokens found'], 404);
        }
        // foreach ($tokens as $token) {
        //     $fcm->send($token, 'Mari Kita Crot', 'Crot sana crot sini crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini crot sana crot situ crot sini');
        // }
        // $fcm->send($token, 'Test Notification', 'This is a test notification from the admin dashboard.');
        return response()->json(['message' => 'Notification sent successfully']);
    }
}
