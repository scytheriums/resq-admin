<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-roles'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-roles'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-roles'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-roles'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::withCount('users');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($role) {
                    $edit = '';
                    $delete = '';
                    
                    if (auth()->user()->can('update-roles')) {
                        $edit = '<a href="' . route('admin.roles.edit', $role->id) . '" class="text-body">';
                        $edit .= '<i class="ti ti-edit ti-sm me-2"></i>';
                        $edit .= '</a>';
                    }
                    
                    if (auth()->user()->can('delete-roles')) {
                        $delete = '<a href="#" class="text-body delete-record btn-delete" ';
                        $delete .= 'data-bs-toggle="modal" ';
                        $delete .= 'data-bs-target="#deleteModal" ';
                        $delete .= 'data-url="' . route('admin.roles.destroy', $role->id) . '" ';
                        $delete .= 'data-name="' . $role->name . '">';
                        $delete .= '<i class="ti ti-trash ti-sm mx-2"></i>';
                        $delete .= '</a>';
                    }
                    
                    return '<div class="d-flex align-items-center">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $title = 'Roles';
        return view('admin.roles.index', compact('title'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('-', $permission->slug)[0];
        });
        $title = 'Create Role';
        return view('admin.roles.create', compact('permissions', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('-', $permission->slug)[0];
        });
        $role->load('permissions');
        $title = 'Edit Role';
        return view('admin.roles.edit', compact('role', 'permissions', 'title'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
