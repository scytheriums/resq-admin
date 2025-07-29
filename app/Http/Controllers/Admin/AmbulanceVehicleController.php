<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmbulanceVehicle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AmbulanceVehicleController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['permission:read-ambulance-vehicle'], ['only' => ['index', 'show']]);
        // $this->middleware(['permission:create-ambulance-vehicle'], ['only' => ['create', 'store']]);
        // $this->middleware(['permission:update-ambulance-vehicle'], ['only' => ['edit', 'update']]);
        // $this->middleware(['permission:delete-ambulance-vehicle'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vehicles = AmbulanceVehicle::query();
            return DataTables::of($vehicles)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (auth()->user()->can('update-ambulance-type')) {
                        $edit = '<a class="text-body" href="' . route('admin.ambulance-vehicles.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    }
                    if (auth()->user()->can('delete-ambulance-type')) {
                        $delete = '<a href="" class="text-body delete-record" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.ambulance-vehicles.destroy', $data->id) . '" data-name="' . $data->name . '"><i class="ti ti-trash ti-sm mx-2"></i></a>';
                    }
                    return '<div class="d-flex align-items-center justify-content-center">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $title = 'Kendaraan Ambulance';
        return view('admin.ambulance-vehicles.index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Kendaraan Ambulance';
        return view('admin.ambulance-vehicles.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_name' => 'required|string|max:255',
        ], [
            'vehicle_name.required' => 'Nama kendaraan wajib diisi',
        ]);

        try {
            AmbulanceVehicle::create($validated);
            
            return redirect()
                ->route('admin.ambulance-vehicles.index')
                ->with('success', 'Kendaraan ambulance berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        }
    }

    public function show(AmbulanceVehicle $ambulanceVehicle)
    {
        return view('admin.ambulance-vehicles.show', compact('ambulanceVehicle'));
    }

    public function edit(AmbulanceVehicle $ambulanceVehicle)
    {
        $title = 'Edit Kendaraan Ambulance';
        return view('admin.ambulance-vehicles.edit', compact('title', 'ambulanceVehicle'));
    }

    public function update(Request $request, AmbulanceVehicle $ambulanceVehicle)
    {
        $validated = $request->validate([
            'vehicle_name' => 'required|string|max:255',
        ], [
            'vehicle_name.required' => 'Nama kendaraan wajib diisi'
        ]);

        try {
            $ambulanceVehicle->update($validated);
            $ambulanceVehicle->ambulanceTypes()->update([
                'name' => $ambulanceVehicle->vehicle_name
            ]);
            
            return redirect()
                ->route('admin.ambulance-vehicles.index')
                ->with('success', 'Data kendaraan ambulance berhasil diperbarui');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan. ' . $e->getMessage());
        }
    }

    public function destroy(AmbulanceVehicle $ambulanceVehicle)
    {
        try {
            $ambulanceVehicle->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kendaraan ambulance berhasil dihapus'
                ]);
            }
            
            return redirect()
                ->route('admin.ambulance-vehicles.index')
                ->with('success', 'Kendaraan ambulance berhasil dihapus');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus kendaraan. ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->with('error', 'Gagal menghapus kendaraan. ' . $e->getMessage());
        }
    }
}
