<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TelegramService;
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
            $orders = Order::query()->with(['user', 'driver', 'ambulanceType', 'purpose', 'review']);
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '<a class="text-body" href="' . route('admin.orders.show', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    // $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.orders.destroy', $data->id) . '" data-name="Order #' . $data->order_number . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    $delete = '';
                    return ' <div class="d-flex align-items-center justify-content-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->editColumn('order_number', function ($data) {
                    return '<div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">' . $data->order_number . '</h6>
                                    <small class="text-muted">' . $data->order_date->translatedFormat('l, d F Y, H:i') . '</small>
                                </div>
                            </div>';
                })
                ->editColumn('name', function ($data) {
                    return '<div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0">' . $data->name . '</h6>
                                    <small class="text-muted">' . $data->whatsapp_number . '</small>
                                </div>
                            </div>';
                })
                ->editColumn('order_status', function ($data) {
                    return view('components.status-badge', [
                        'label' => $data->order_status_label,
                        'class' => $data->order_status_class
                    ]);
                })
                ->editColumn('payment_status', function ($data) {
                    return view('components.status-badge', [
                        'label' => $data->payment_status_label,
                        'class' => $data->payment_status_class
                    ]);
                })
                ->addColumn('driver', function ($data) {
                    if ($data->driver) {
                        return '<div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">' . $data->driver->name . '</h6>
                                        <small class="text-muted">' . $data->driver->phone_number . '</small>
                                    </div>
                                </div>';
                    }
                    return '<span class="text-muted">Not Assigned</span>';
                })
                ->rawColumns(['action', 'order_status', 'payment_status', 'driver', 'order_number', 'name'])
                ->make(true);
        }
        $title = 'Orders';
        return view('admin.orders.index', compact('title'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'driver', 'ambulanceType', 'purpose', 'additionalServices', 'review']);
        $title = 'Detail Pesanan #' . $order->order_number;
        
        // Get available drivers and additional services
        $drivers = Driver::isAvailable()->get();
        $additionalServices = AdditionalService::all();
        
        return view('admin.orders.show', compact('order', 'title', 'drivers', 'additionalServices'));
    }
    
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'nullable|exists:drivers,id',
            'additional_services' => 'nullable|array',
            'additional_services.*' => 'exists:additional_services,id'
        ]);
        
        // Update driver if provided
        if ($request->has('driver_id')) {
            $order->driver_id = $request->driver_id;
        }
        
        // Update additional services if provided
        if ($request->has('additional_services')) {
            $additionalServices = AdditionalService::whereIn('id', $request->additional_services)->get();
            $syncData = [];
            
            foreach ($additionalServices as $service) {
                $syncData[$service->id] = ['price' => $service->price];
            }
            
            $order->additionalServices()->sync($syncData);
            $order->additional_services_fee = $additionalServices->sum('price');
            
            // Recalculate total bill
            $order->total_bill = $order->base_price + $order->booking_fee + $order->additional_services_fee;
        }
        
        $order->order_status = 'confirmed';
        $order->payment_status = 'final_payment_pending';
        $order->driver->is_available = false;
        $order->push();
        
        // Send Telegram notification
        $telegramService = new TelegramService($order->driver->telegram_chat_id);
        $message = "<b>🚑 Pesanan Baru Dikonfirmasi</b>\n";
        $message .= "<b>ID Pesanan:</b> #{$order->order_number}\n";
        $message .= "<b>Nama Pemesan:</b> {$order->name}\n";
        $message .= "<b>No. HP:</b> {$order->whatsapp_number}\n";
        $message .= "<b>Alamat Penjemputan:</b> {$order->pickup_address}\n";
        $message .= "<b>Tujuan:</b> " . ($order->destination_address ?? '-') . "\n";
        $message .= "<b>Status:</b> " . ucfirst(str_replace('_', ' ', $order->order_status_label)). "\n\n\n";
        $message .= "<b>Driver Dimohon untuk standby</b>";
        
        $telegramService->sendMessage($message);
        
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function complete(Order $order)
    {
        $order->order_status = 'completed';
        $order->driver->is_available = true;
        $order->push();
        
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diselesaikan.');
    }

    public function deleteReview(Order $order)
    {
        $order->review()->delete();
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
