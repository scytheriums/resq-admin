<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $drivers = Driver::query();
            return DataTables::of($drivers)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '<a class="text-body" href="' . route('admin.drivers.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.drivers.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge ' . ($data->status === 'available' ? 'bg-success' : ($data->status === 'on_duty' ? 'bg-warning' : 'bg-danger')) . '">' . 
                        ($data->status === 'available' ? 'Available' : ($data->status === 'on_duty' ? 'On Duty' : 'Unavailable')) . 
                    '</span>';
                })
                ->addColumn('vehicle', function ($data) {
                    return $data->vehicle_brand . ' ' . $data->vehicle_model . ' (' . $data->vehicle_type . ')';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $title = 'Drivers';
        return view('admin.drivers.index', compact('title'));
    }

    public function show(Driver $driver)
    {
        $driver->load(['orders']);
        $title = 'Driver Details';
        return view('admin.drivers.show', compact('driver', 'title'));
    }

    public function create()
    {
        $title = 'Create Driver';
        return view('admin.drivers.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:drivers,email',
            'license_number' => 'required|string|unique:drivers,license_number',
            'license_expiry_date' => 'required|date',
            'vehicle_number' => 'required|string|unique:drivers,vehicle_number',
            'vehicle_type' => 'required|string',
            'vehicle_brand' => 'required|string',
            'vehicle_model' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'license_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,on_duty,unavailable'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->photo->store('drivers/photos');
        }

        if ($request->hasFile('license_photo')) {
            $validated['license_photo'] = $request->license_photo->store('drivers/licenses');
        }

        if ($request->hasFile('vehicle_photo')) {
            $validated['vehicle_photo'] = $request->vehicle_photo->store('drivers/vehicles');
        }

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver created successfully');
    }

    public function edit(Driver $driver)
    {
        $title = 'Edit Driver';
        return view('admin.drivers.edit', compact('driver', 'title'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'license_expiry_date' => 'required|date',
            'vehicle_number' => 'required|string|unique:drivers,vehicle_number,' . $driver->id,
            'vehicle_type' => 'required|string',
            'vehicle_brand' => 'required|string',
            'vehicle_model' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'license_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vehicle_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:available,on_duty,unavailable'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->photo->store('drivers/photos');
        }

        if ($request->hasFile('license_photo')) {
            $validated['license_photo'] = $request->license_photo->store('drivers/licenses');
        }

        if ($request->hasFile('vehicle_photo')) {
            $validated['vehicle_photo'] = $request->vehicle_photo->store('drivers/vehicles');
        }

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver updated successfully');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Driver deleted successfully');
    }
}
