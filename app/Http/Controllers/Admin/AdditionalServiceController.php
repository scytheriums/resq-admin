<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdditionalService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdditionalServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-additional-service'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-additional-service'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-additional-service'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-additional-service'], ['only' => ['destroy']]);
    }   

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
                ->editColumn('price', function ($data) {
                    return 'Rp ' . number_format($data->price);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $title = 'Additional Services';
        return view('admin.additional-services.index', compact('title'));
    }

    public function show(AdditionalService $additionalService)
    {
        $title = 'Additional Service Details';
        return view('admin.additional-services.show', compact('additionalService', 'title'));
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

    public function edit(AdditionalService $additionalService)
    {
        $title = 'Edit Additional Service';
        return view('admin.additional-services.edit', compact('additionalService', 'title'));
    }

    public function update(Request $request, AdditionalService $additionalService)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:additional_services,name,' . $additionalService->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($validated);
        return redirect()->route('admin.additional-services.index')->with('success', 'Service updated successfully');
    }

    public function destroy(AdditionalService $additionalService)
    {
        $additionalService->delete();
        return redirect()->route('admin.additional-services.index')->with('success', 'Service deleted successfully');
    }
}
