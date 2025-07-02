@extends('layouts.master')

@section('vendor-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}" />
@endsection

@push('page-js')
    @include('layouts.script_datatables')
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-icon">
                            <i class="bx bx-package"></i>
                        </div>
                        <div class="card-stats">
                            <h4 class="card-title">{{ $summary['todayOrders'] }}</h4>
                            <p class="card-text text-muted">Today's Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-icon">
                            <i class="bx bx-time"></i>
                        </div>
                        <div class="card-stats">
                            <h4 class="card-title">{{ $summary['ongoingOrders'] }}</h4>
                            <p class="card-text text-muted">Ongoing Orders</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-icon">
                            <i class="bx bx-user"></i>
                        </div>
                        <div class="card-stats">
                            <h4 class="card-title">{{ $summary['availableDrivers'] }}</h4>
                            <p class="card-text text-muted">Available Drivers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-icon">
                            <i class="bx bx-money"></i>
                        </div>
                        <div class="card-stats">
                            <h4 class="card-title">{{ number_format($summary['todayRevenue'], 2) }}</h4>
                            <p class="card-text text-muted">Today's Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <!-- Order Trend Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Order Trend</h4>
                </div>
                <div class="card-body">
                    <div id="order-trend-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <!-- Recent Orders -->
        <div class="col-xl-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Orders</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>
                                        <x-status-badge :class="$order->order_status_class" :label="$order->order_status_label" />
                                    </td>
                                    <td>
                                        <x-status-badge :class="$order->payment_status_class" :label="$order->payment_status_label" />
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Driver Status -->
        <div class="col-xl-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Driver Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-3">
                                    <span class="badge bg-label-success p-2 rounded-circle">
                                        <i class="ti ti-user-check fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $driverStatus['available'] }}</h4>
                                    <small class="text-muted">Available</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-3">
                                    <span class="badge bg-label-primary p-2 rounded-circle">
                                        <i class="ti ti-car fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">0</h4>
                                    <small class="text-muted">On Duty</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="me-3">
                                    <span class="badge bg-label-danger p-2 rounded-circle">
                                        <i class="ti ti-user-x fs-4"></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $driverStatus['unavailable'] }}</h4>
                                    <small class="text-muted">Unavailable</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection