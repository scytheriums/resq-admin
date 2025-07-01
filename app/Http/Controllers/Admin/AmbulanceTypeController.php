<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AmbulanceTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ambulanceTypes = AmbulanceType::query();
            return DataTables::of($ambulanceTypes)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '<a class="text-body" href="' . route('admin.ambulance-types.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('backadmin.category.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->rawColumns(['action'])
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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ambulance_types,name',
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        AmbulanceType::create($validated);
        return redirect()->route('admin.ambulance-types.index')->with('success', 'Ambulance Type created successfully');
    }

    public function edit(AmbulanceType $ambulanceType)
    {
        $title = 'Edit Ambulance Type';
        return view('admin.ambulance-types.edit', compact('ambulanceType'));
    }

    public function update(Request $request, AmbulanceType $ambulanceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ambulance_types,name,' . $ambulanceType->id,
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $ambulanceType->update($validated);
        return redirect()->route('admin.ambulance-types.index')->with('success', 'Ambulance Type updated successfully');
    }

    public function destroy(AmbulanceType $ambulanceType)
    {
        $ambulanceType->delete();
        return redirect()->route('admin.ambulance-types.index')->with('success', 'Ambulance Type deleted successfully');
    }
}
