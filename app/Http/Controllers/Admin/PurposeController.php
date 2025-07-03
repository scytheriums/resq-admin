<?php

namespace App\Http\Controllers\Admin;

use App\Models\Purpose;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurposeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-purpose'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-purpose'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-purpose'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-purpose'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $purposes = Purpose::query()->withCount('orders');
            return DataTables::of($purposes)
                ->addIndexColumn()
                ->editColumn('tarif', function ($data) {
                    return 'Rp ' . number_format($data->tarif, 0);
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (auth()->user()->can('update-purpose')) {
                        $edit = '<a class="text-body" href="' . route('admin.purposes.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    }

                    if (auth()->user()->can('delete-purpose')) {
                        $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.purposes.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    }

                    return ' <div class="d-flex align-items-center">
                                ' . $edit . '
                                ' . $delete . '
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $title = 'Purposes';
        return view('admin.purposes.index', compact('title'));
    }

    public function show(Purpose $purpose)
    {
        $title = 'Purpose Details';
        return view('admin.purposes.show', compact('purpose', 'title'));
    }

    public function create()
    {
        $title = 'Create Purpose';
        return view('admin.purposes.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:purposes,name',
            'tarif' => 'required|numeric|min:0',
        ]);

        Purpose::create($validated);
        return redirect()->route('admin.purposes.index')->with('success', 'Purpose created successfully');
    }

    public function edit(Purpose $purpose)
    {
        $title = 'Edit Purpose';
        return view('admin.purposes.edit', compact('purpose', 'title'));
    }

    public function update(Request $request, Purpose $purpose)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:purposes,name,' . $purpose->id,
            'tarif' => 'required|numeric|min:0',
        ]);

        $purpose->update($validated);
        return redirect()->route('admin.purposes.index')->with('success', 'Purpose updated successfully');
    }

    public function destroy(Purpose $purpose)
    {
        $purpose->delete();
        return redirect()->route('admin.purposes.index')->with('success', 'Purpose deleted successfully');
    }
}
