<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdditionalServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = AdditionalService::query();
            return DataTables::of($services)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '<a class="text-body" href="' . route('admin.additional-services.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.additional-services.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $title = 'Additional Services';
        return view('admin.additional-services.index', compact('title'));
    }

    public function show(AdditionalService $service)
    {
        $title = 'Additional Service Details';
        return view('admin.additional-services.show', compact('service', 'title'));
    }

    public function create()
    {
        $title = 'Create Additional Service';
        return view('admin.additional-services.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:additional_services,name',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        AdditionalService::create($validated);
        return redirect()->route('admin.additional-services.index')->with('success', 'Service created successfully');
    }

    public function edit(AdditionalService $service)
    {
        $title = 'Edit Additional Service';
        return view('admin.additional-services.edit', compact('service', 'title'));
    }

    public function update(Request $request, AdditionalService $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:additional_services,name,' . $service->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($validated);
        return redirect()->route('admin.additional-services.index')->with('success', 'Service updated successfully');
    }

    public function destroy(AdditionalService $service)
    {
        $service->delete();
        return redirect()->route('admin.additional-services.index')->with('success', 'Service deleted successfully');
    }
}
