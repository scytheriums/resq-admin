<?php

namespace App\Http\Controllers\Admin;

use App\Models\Driver;
use App\Models\AmbulanceType;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-driver'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-driver'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-driver'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-driver'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $drivers = Driver::query()->with('ambulanceType');
            return DataTables::of($drivers)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';

                    if (auth()->user()->can('update-driver')) {
                        $edit = '<a class="text-body" href="' . route('admin.drivers.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    }

                    if (auth()->user()->can('delete-driver')) {
                        $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.drivers.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    }

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
        $ambulanceTypes = AmbulanceType::all();
        $title = 'Create Driver';
        return view('admin.drivers.create', compact('ambulanceTypes', 'title'));
    }

    public function getProvinces()
    {
        $provinces = Province::select('code', 'name')
        ->when(request('search'), function($record, $search) {
            return $record->where('name', 'ilike', '%'.$search.'%');
        })
        ->get();
        
        return response()->json($provinces);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('province_code', $request->province_code)
            ->select('code', 'name')
            ->when(request('search'), function($record, $search) {
                return $record->where('name', 'ilike', '%'.$search.'%');
            })
            ->get();

        return response()->json($cities);
    }

    public function getDistricts(Request $request)
    {
        $districts = District::where('city_code', $request->city_code)
            ->select('code', 'name')
            ->when(request('search'), function($record, $search) {
                return $record->where('name', 'ilike', '%'.$search.'%');
            })
            ->get();

        return response()->json($districts);
    }

    public function getVillages(Request $request)
    {
        $villages = Village::where('district_code', $request->district_code)
            ->select('code', 'name')
            ->when(request('search'), function($record, $search) {
                return $record->where('name', 'ilike', '%'.$search.'%');
            })
            ->get();

        return response()->json($villages);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'telegram_chat_id' => 'nullable|string|max:255',
            'license_plate' => 'nullable|string|max:20',
            'ambulance_type_id' => 'required|exists:ambulance_types,id',
            'base_address' => 'required|string',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required'
        ]);

        // // Set default status
        // $validated['status'] = 'available';
        
        Driver::create($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver created successfully');
    }

    public function edit(Driver $driver)
    {
        $title = 'Edit Driver';
        $ambulanceTypes = AmbulanceType::orderBy('name')->get();
        return view('admin.drivers.edit', compact('title', 'driver', 'ambulanceTypes'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'telegram_chat_id' => 'nullable|string|max:100',
            'license_plate' => 'nullable|string|max:20',
            'ambulance_type_id' => 'required',
            'base_address' => 'required|string|max:500',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required'
        ]);

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver updated successfully');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')->with('success', 'Driver deleted successfully');
    }
}
