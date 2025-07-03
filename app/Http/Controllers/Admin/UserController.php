<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-users'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-users'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-users'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-users'], ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles');
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function($user) {
                    $roles = '';
                    foreach ($user->roles as $role) {
                        $roles .= '<span class="badge bg-primary">' . $role->name . '</span> ';
                    }
                    return $roles;
                })
                ->addColumn('action', function($user) {
                    $edit = '';
                    $delete = '';
                    
                    if (auth()->user()->can('update-users')) {
                        $edit = '<a href="' . route('admin.users.edit', $user->id) . '" class="text-body">';
                        $edit .= '<i class="ti ti-edit ti-sm me-2"></i>';
                        $edit .= '</a>';
                    }
                    
                    if (auth()->user()->can('delete-users')) {
                        $delete = '<a href="#" class="text-body delete-record btn-delete" ';
                        $delete .= 'data-bs-toggle="modal" ';
                        $delete .= 'data-bs-target="#deleteModal" ';
                        $delete .= 'data-url="' . route('admin.users.destroy', $user->id) . '" ';
                        $delete .= 'data-name="' . $user->name . '">';
                        $delete .= '<i class="ti ti-trash ti-sm mx-2"></i>';
                        $delete .= '</a>';
                    }
                    
                    return '<div class="d-flex align-items-center justify-content-center">' . $edit . $delete . '</div>';
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }
        
        $title = 'Daftar Pengguna';
        return view('admin.users.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = DB::table('roles')->select('id', 'name', 'slug')->get();
        $title = 'Tambah Pengguna';
        return view('admin.users.create', compact('roles', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $title = 'Detail Pengguna';
        return view('admin.users.show', compact('user', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = DB::table('roles')->select('id', 'name', 'slug')->get();
        $title = 'Ubah Pengguna';
        return view('admin.users.edit', compact('user', 'roles', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $user->roles()->sync($request->roles ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
