<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use App\Models\AmbulanceType;
use App\Models\Purpose;
use App\Models\AdditionalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::query()->with(['user', 'driver', 'ambulanceType', 'purpose', 'additionalServices']);
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '<a class="text-body" href="' . route('admin.orders.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.orders.destroy', $data->id) . '" data-name="Order #' . $data->order_number . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->addColumn('customer', function ($data) {
                    return '<div class="d-flex align-items-center">
                                <div class="avatar mr-1">
                                    <img src="' . asset('images/avatars/user.png') . '" alt="avatar" height="32" width="32">
                                </div>
                                <div>
                                    <h6 class="mb-0">' . $data->user->name . '</h6>
                                    <small class="text-muted">' . $data->user->email . '</small>
                                </div>
                            </div>';
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge badge-' . ($data->status === 'completed' ? 'success' : ($data->status === 'cancelled' ? 'danger' : 'warning')) . '">' . $data->status . '</span>';
                })
                ->addColumn('payment_status', function ($data) {
                    return '<span class="badge badge-' . ($data->payment_status === 'full_payment_paid' ? 'success' : 'warning') . '">' . $data->payment_status . '</span>';
                })
                ->addColumn('driver', function ($data) {
                    if ($data->driver) {
                        return '<div class="d-flex align-items-center">
                                    <div class="avatar mr-1">
                                        <img src="' . asset('images/avatars/user.png') . '" alt="avatar" height="32" width="32">
                                    </div>
                                    <div>
                                        <h6 class="mb-0">' . $data->driver->name . '</h6>
                                        <small class="text-muted">' . $data->driver->phone . '</small>
                                    </div>
                                </div>';
                    }
                    return '<span class="text-muted">Not Assigned</span>';
                })
                ->rawColumns(['action', 'customer', 'status', 'payment_status', 'driver'])
                ->make(true);
        }
        $title = 'Orders';
        return view('admin.orders.index', compact('title'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'driver', 'ambulanceType', 'purpose', 'additionalServices']);
        $title = 'Order Details';
        return view('admin.orders.show', compact('order', 'title'));
    }

    public function create()
    {
        $drivers = Driver::where('status', 'available')->get();
        $ambulanceTypes = AmbulanceType::all();
        $purposes = Purpose::all();
        $additionalServices = AdditionalService::all();
        $title = 'Create Order';
        return view('admin.orders.create', compact('drivers', 'ambulanceTypes', 'purposes', 'additionalServices', 'title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'ambulance_type_id' => 'required|exists:ambulance_types,id',
            'purpose_id' => 'required|exists:purposes,id',
            'pickup_location' => 'required|array',
            'destination_location' => 'required|array',
            'notes' => 'nullable|string',
            'base_price' => 'required|numeric',
            'booking_fee' => 'required|numeric',
            'additional_services_fee' => 'required|numeric',
            'total_bill' => 'required|numeric',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $order = Order::create($validated);
            
            if ($request->has('additional_services')) {
                $order->additionalServices()->attach($request->additional_services);
            }
        });

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully');
    }

    public function edit(Order $order)
    {
        $drivers = Driver::where('status', 'available')->get();
        $ambulanceTypes = AmbulanceType::all();
        $purposes = Purpose::all();
        $additionalServices = AdditionalService::all();
        $title = 'Edit Order';
        return view('admin.orders.edit', compact('order', 'drivers', 'ambulanceTypes', 'purposes', 'additionalServices', 'title'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|in:created,booked,assigned_to_driver,in_progress_pickup,in_progress_deliver,completed,cancelled',
            'payment_status' => 'required|in:booking_fee_pending,booking_fee_paid,full_payment_pending,full_payment_paid',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully');
    }
}
