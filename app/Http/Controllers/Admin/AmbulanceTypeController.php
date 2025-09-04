<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmbulanceType;
use App\Models\Provider;
use App\Models\Purpose;
use App\Models\Driver;
use App\Models\AmbulanceVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AmbulanceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-ambulance-type'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-ambulance-type'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-ambulance-type'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-ambulance-type'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ambulanceTypes = AmbulanceType::with(['ambulanceVehicle'])
                ->withCount('tarifs')
                ->withMin('tarifs', 'tarif')
                ->withMax('tarifs', 'tarif');
                
            $purposes = Purpose::pluck('name','id');
            
            return DataTables::of($ambulanceTypes)
                ->addIndexColumn()
                ->addColumn('vehicle_name', function ($data){
                    return $data->ambulanceVehicle->vehicle_name ?? '-';
                })
                ->addColumn('tarif_range', function ($data) {
                    if ($data->tarifs_count > 0) {
                        return 'Rp ' . number_format($data->tarifs_min_tarif, 0, ',', '.') . ' - Rp ' . 
                               number_format($data->tarifs_max_tarif, 0, ',', '.');
                    }
                    return '-';
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (auth()->user()->can('update-ambulance-type')) {
                        $edit = '<a class="text-body" href="' . route('admin.ambulance-types.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    }

                    if (auth()->user()->can('delete-ambulance-type')) {
                        $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.ambulance-types.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    }

                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->editColumn('free_tarif_for_purpose', function ($data) use ($purposes) {
                    $html = '';
                    if($data->free_tarif_for_purpose && count($data->free_tarif_for_purpose)) {
                        $gratis = '';
                        foreach($purposes as $purpose) {
                            $gratis .= '<span class="badge bg-success d-block" style="margin-top: 5px;">'.$purpose.'</span>';
                        }
                        $html .= '<div class="d-block">'.$gratis.'</div>';
                    } else {
                        $html = 'N/A';
                    }
                    
                    return $html;
                })
                ->rawColumns(['action', 'free_tarif_for_purpose', 'tarif_range', 'vehicle_name'])
                ->make(true);
        }
        $title = 'Ambulance Types';

        return view('admin.ambulance-types.index', compact('title'));
    }

    public function show(AmbulanceType $ambulanceType)
    {
        $title = 'Ambulance Type Details';
        return view('admin.ambulance-types.show', compact('ambulanceType', 'title'));
    }

    public function create()
    {
        $title = 'Create Ambulance Type';
        $ambulanceVehicles = AmbulanceVehicle::all();
        return view('admin.ambulance-types.create', compact('title', 'ambulanceVehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:ambulance_vehicles,id',
            'tarifs' => 'required|array|min:1',
            'tarifs.*.min_distance' => 'required|numeric|min:0',
            'tarifs.*.max_distance' => 'required|numeric|min:0|gte:tarifs.*.min_distance',
            'tarifs.*.tarif' => 'required|numeric|min:0',
            'free_tarif_for_purpose' => 'nullable|array',
            'free_tarif_for_purpose.*' => 'exists:purposes,id'
        ]);

        // Start database transaction
        return DB::transaction(function () use ($validated) {
            // Create the ambulance type
            $ambulanceType = new AmbulanceType([
                'vehicle_id' => $validated['vehicle_id'],
                'name' => AmbulanceVehicle::find($validated['vehicle_id'])->vehicle_name,
                'free_tarif_for_purpose' => $validated['free_tarif_for_purpose'] ?? null,
                'tarif_dalam_kota' => 0,
                'tarif_km_luar_kota' => 0,
                'tarif_km_luar_provinsi' => 0,
                'provider_id' => Provider::first()->id
            ]);
            
            $ambulanceType->save();

            // Create tarifs
            foreach ($validated['tarifs'] as $tarifData) {
                $ambulanceType->tarifs()->create([
                    'min_distance' => $tarifData['min_distance'],
                    'max_distance' => $tarifData['max_distance'],
                    'provider_id' => $ambulanceType->provider_id,
                    'tarif' => $tarifData['tarif']
                ]);
            }

            return redirect()
                ->route('admin.ambulance-types.index')
                ->with('success', 'Tipe Ambulance berhasil ditambahkan');
        });
    }

    public function edit(AmbulanceType $ambulanceType)
    {
        $title = 'Edit Ambulance Type';
        $ambulanceVehicles = AmbulanceVehicle::all();
        return view('admin.ambulance-types.edit', compact('ambulanceType', 'title', 'ambulanceVehicles'));
    }

    public function update(Request $request, AmbulanceType $ambulanceType)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:ambulance_vehicles,id',
            'tarifs' => 'required|array|min:1',
            'tarifs.*.min_distance' => 'required|numeric|min:0',
            'tarifs.*.max_distance' => 'required|numeric|min:0|gte:tarifs.*.min_distance',
            'tarifs.*.tarif' => 'required|numeric|min:0',
            'free_tarif_for_purpose' => 'nullable|array',
            'free_tarif_for_purpose.*' => 'exists:purposes,id'
        ]);

        // Start database transaction
        return DB::transaction(function () use ($validated, $ambulanceType) {
            // Update the ambulance type
            $ambulanceType->update([
                'vehicle_id' => $validated['vehicle_id'],
                'name' => AmbulanceVehicle::find($validated['vehicle_id'])->vehicle_name,
                'free_tarif_for_purpose' => $validated['free_tarif_for_purpose'] ?? null
            ]);

            // Delete existing tarifs
            $ambulanceType->tarifs()->delete();

            // Create new tarifs
            foreach ($validated['tarifs'] as $tarifData) {
                $ambulanceType->tarifs()->create([
                    'min_distance' => $tarifData['min_distance'],
                    'max_distance' => $tarifData['max_distance'],
                    'provider_id' => $ambulanceType->provider_id,
                    'tarif' => $tarifData['tarif']
                ]);
            }

            return redirect()
                ->route('admin.ambulance-types.index')
                ->with('success', 'Tipe Ambulance berhasil diperbarui');
        });
    }

    public function destroy(AmbulanceType $ambulanceType)
    {
        try {
            DB::transaction(function () use ($ambulanceType) {
                Driver::where('ambulance_type_id', $ambulanceType->id)->update(['ambulance_type_id' => null]);
                $ambulanceType->delete();
            });

            return redirect()->route('admin.ambulance-types.index')->with('success', 'Ambulance Type deleted successfully');
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            // Log::error('Failed to delete ambulance type: ' . $e->getMessage());
            return redirect()->route('admin.ambulance-types.index')->with('error', 'Failed to delete Ambulance Type. It might be in use.');
        }
    }
}
