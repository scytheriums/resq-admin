<?php

namespace App\Http\Controllers\Admin;

use App\Models\Affiliators;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;

class AffiliatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-affiliators'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-affiliators'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-affiliators'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-affiliators'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $affiliators = Affiliators::query();
            
            return DataTables::of($affiliators)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    return $data->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>';
                })
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';
                    if (auth()->user()->can('update-affiliators')) {
                        $edit = '<a class="text-body" href="' . route('admin.affiliators.edit', $data->id) . '"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    }

                    if (auth()->user()->can('delete-affiliators')) {
                        $delete = '<a href="" class="text-body delete-record btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.affiliators.destroy', $data->id) . '" data-name="' . $data->name . '"> <i class="ti ti-trash ti-sm mx-2"></i></a>';
                    }

                    return '<div class="d-flex align-items-center">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $title = 'Daftar Affiliator';
        return view('admin.affiliators.index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Affiliator';
        return view('admin.affiliators.create', compact('title'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:affiliators,code',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:affiliators,email',
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required',
            'full_address' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Affiliators::create($validated);

        return redirect()
            ->route('admin.affiliators.index')
            ->with('success', 'Affiliator berhasil ditambahkan');
    }

    public function show(Affiliators $affiliator)
    {
        $title = 'Detail Affiliator';
        return view('admin.affiliators.show', compact('title', 'affiliator'));
    }

    public function edit(Affiliators $affiliator)
    {
        $title = 'Edit Affiliator';
        return view('admin.affiliators.edit', compact(
            'title', 
            'affiliator'
        ));
    }

    public function update(Request $request, Affiliators $affiliator)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:affiliators,code,' . $affiliator->id,
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:affiliators,email,' . $affiliator->id,
            'province_code' => 'required',
            'city_code' => 'required',
            'district_code' => 'required',
            'village_code' => 'required',
            'full_address' => 'required|string'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $affiliator->update($validated);

        return redirect()
            ->route('admin.affiliators.index')
            ->with('success', 'Affiliator berhasil diperbarui');
    }

    public function destroy(Affiliators $affiliator)
    {
        $affiliator->delete();
        return response()->json(['success' => true, 'message' => 'Affiliator berhasil dihapus']);
    }
}
