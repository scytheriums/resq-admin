<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $destinations = Destination::query();
            return DataTables::of($destinations)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '<a class="text-body" href="' . route('admin.destinations.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.destinations.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->addColumn('location', function ($data) {
                    return $data->city . ', ' . $data->province . '<br>' . $data->postal_code;
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge ' . ($data->is_active ? 'bg-success' : 'bg-danger') . '">' . ($data->is_active ? 'Active' : 'Inactive') . '</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $title = 'Destinations';
        return view('admin.destinations.index', compact('title'));
    }

    public function create()
    {
        $title = 'Create Destination';
        return view('admin.destinations.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        Destination::create($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination created successfully');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')
            ->with('success', 'Destination updated successfully');
    }

    public function destroy(Destination $destination)
    {
        DB::beginTransaction();
        try {
            $destination->delete();
            DB::commit();
            return response()->json(['message' => 'Destination deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete destination'], 500);
        }
    }
}
