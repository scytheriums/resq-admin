<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmbulanceType;
use App\Models\Driver;
use App\Models\Purpose;
use Illuminate\Http\Request;
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
            $ambulanceTypes = AmbulanceType::query();
            $purposes = Purpose::pluck('name','id');
            return DataTables::of($ambulanceTypes)
                ->addIndexColumn()
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
                ->editColumn('tarif_dalam_kota', function ($data) use ($purposes) {
                    $html = '<div class="d-block">Rp ' . number_format($data->tarif_dalam_kota, 2). '</div>';
                    
                    return $html;
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
                ->editColumn('tarif_km_luar_kota', function ($data) {
                    return 'Rp ' . number_format($data->tarif_km_luar_kota, 2);
                })
                ->editColumn('tarif_km_luar_provinsi', function ($data) {
                    return 'Rp ' . number_format($data->tarif_km_luar_provinsi, 2);
                })
                ->rawColumns(['action', 'free_tarif_for_purpose', 'tarif_dalam_kota'])
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
        return view('admin.ambulance-types.create', compact('title'));
    }

    public function store(Request $request)
    {
        // DB::statement("SELECT setval(pg_get_serial_sequence('ambulance_types', 'id'), coalesce(max(id),0) + 1, false) FROM ambulance_types;");
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ambulance_types,name',
            'tarif_dalam_kota' => 'required|numeric|min:0',
            'tarif_km_luar_kota' => 'required|numeric|min:0',
            'tarif_km_luar_provinsi' => 'required|numeric|min:0',
            'free_tarif_for_purpose' => 'nullable|array',
            'free_tarif_for_purpose.*' => 'exists:purposes,id'
        ]);

        $ambulanceType = AmbulanceType::create($validated);
        return redirect()->route('admin.ambulance-types.index')->with('success', 'Ambulance Type created successfully');
    }

    public function edit(AmbulanceType $ambulanceType)
    {
        $title = 'Edit Ambulance Type';
        return view('admin.ambulance-types.edit', compact('ambulanceType', 'title'));
    }

    public function update(Request $request, AmbulanceType $ambulanceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ambulance_types,name,' . $ambulanceType->id,
            'tarif_dalam_kota' => 'required|numeric|min:0',
            'tarif_km_luar_kota' => 'required|numeric|min:0',
            'tarif_km_luar_provinsi' => 'required|numeric|min:0',
            'free_tarif_for_purpose' => 'nullable|array',
            'free_tarif_for_purpose.*' => 'exists:purposes,id',
        ]);

        $ambulanceType->update($validated);
        return redirect()->route('admin.ambulance-types.index')
            ->with('success', 'Ambulance type updated successfully');
    }

    public function destroy(AmbulanceType $ambulanceType)
    {
        $ambulanceType->delete();
        return redirect()->route('admin.ambulance-types.index')->with('success', 'Ambulance Type deleted successfully');
    }
}
