@extends('layouts.master')

@section('title', $title)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Admin /</span> {{ $title }}
    </h4>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Driver Information</h5>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('admin.drivers.edit', $driver) }}" class="btn btn-sm btn-primary">
                                <i class="ti ti-edit"></i>
                            </a>
                            <a href="{{ route('admin.drivers.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ti ti-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <p class="mb-0">{{ $driver->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <p class="mb-0">{{ $driver->phone }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <p class="mb-0">{{ $driver->email ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <span class="badge {{ $driver->status === 'available' ? 'bg-success' : ($driver->status === 'on_duty' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $driver->status === 'available' ? 'Available' : ($driver->status === 'on_duty' ? 'On Duty' : 'Unavailable') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">License Number</label>
                                <p class="mb-0">{{ $driver->license_number }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">License Expiry Date</label>
                                <p class="mb-0">{{ $driver->license_expiry_date }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vehicle Number</label>
                                <p class="mb-0">{{ $driver->vehicle_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Vehicle Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vehicle Type</label>
                                <p class="mb-0">{{ $driver->vehicle_type }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vehicle Brand</label>
                                <p class="mb-0">{{ $driver->vehicle_brand }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vehicle Model</label>
                                <p class="mb-0">{{ $driver->vehicle_model }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="mb-3">Photos</h6>
                        <div class="row">
                            @if($driver->photo)
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="{{ asset($driver->photo) }}" class="card-img-top" alt="Driver Photo">
                                        <div class="card-body text-center">
                                            <small>Driver Photo</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($driver->license_photo)
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="{{ asset($driver->license_photo) }}" class="card-img-top" alt="License Photo">
                                        <div class="card-body text-center">
                                            <small>License Photo</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($driver->vehicle_photo)
                                <div class="col-md-4">
                                    <div class="card">
                                        <img src="{{ asset($driver->vehicle_photo) }}" class="card-img-top" alt="Vehicle Photo">
                                        <div class="card-body text-center">
                                            <small>Vehicle Photo</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Recent Orders</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($driver->orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
