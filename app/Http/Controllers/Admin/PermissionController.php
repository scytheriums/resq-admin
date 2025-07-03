<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-permissions'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-permissions'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-permissions'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-permissions'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::query();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($permission) {
                    $edit = '';
                    $delete = '';
                    
                    if (auth()->user()->can('update-permissions')) {
                        $edit = '<a href="' . route('admin.permissions.edit', $permission->id) . '" class="text-body">';
                        $edit .= '<i class="ti ti-edit ti-sm me-2"></i>';
                        $edit .= '</a>';
                    }
                    
                    if (auth()->user()->can('delete-permissions')) {
                        $delete = '<a href="#" class="text-body delete-record btn-delete" ';
                        $delete .= 'data-bs-toggle="modal" ';
                        $delete .= 'data-bs-target="#deleteModal" ';
                        $delete .= 'data-url="' . route('admin.permissions.destroy', $permission->id) . '" ';
                        $delete .= 'data-name="' . $permission->name . '">';
                        $delete .= '<i class="ti ti-trash ti-sm mx-2"></i>';
                        $delete .= '</a>';
                    }
                    
                    return '<div class="d-flex align-items-center">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $title = 'Permissions';
        return view('admin.permissions.index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Permission';
        return view('admin.permissions.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
        ]);

        Permission::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        $title = 'Ubah Permission';
        return view('admin.permissions.edit', compact('permission', 'title'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')->with('error', 'Cannot delete a permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
