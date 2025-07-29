<?php

namespace App\Http\Controllers\Admin;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-providers'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-providers'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-providers'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-providers'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $providers = Provider::query();
            return DataTables::of($providers)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit = '';
                    $delete = '';

                    if (auth()->user()->can('update-providers')) {
                        $edit = '<a class="text-body" href="' . route('admin.providers.edit', $data->id) . '" title="Edit"><i class="ti ti-edit ti-sm me-2"></i></a>';
                    }

                    if (auth()->user()->can('delete-providers')) {
                        $delete = '<a href="" class="text-body delete-record" data-bs-toggle="modal" data-bs-target="#deleteModal" data-url="' . route('admin.providers.destroy', $data->id) . '" data-name="' . $data->name . '" title="Delete"><i class="ti ti-trash ti-sm mx-2"></i></a>';
                    }

                    return '<div class="d-flex align-items-center">'  . $edit . $delete . '</div>';
                })
                ->addColumn('status', function ($data) {
                    return '<span class="badge bg-' . ($data->is_active ? 'success' : 'secondary') . '">' . 
                        ($data->is_active ? 'Aktif' : 'Tidak Aktif') . 
                    '</span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $title = 'Provider';
        return view('admin.provider.index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Provider';
        return view('admin.provider.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|email|unique:providers,email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string'
        ]);

        // Set default is_active to true if not provided
        if (!isset($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $provider = Provider::create($validated);

        $user = [
            'name' => $request->pic_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'provider_id' => $provider->id,
            'password_hash' => Hash::make('password'),
        ];

        $user = User::create($user);

        $user->assignRole('admin');

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider berhasil ditambahkan');
    }

    public function show(Provider $provider)
    {
        $title = 'Detail Provider';
        return view('admin.provider.show', compact('provider', 'title'));
    }

    public function edit(Provider $provider)
    {
        $title = 'Edit Provider';
        return view('admin.provider.edit', compact('provider', 'title'));
    }

    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:providers,email,' . $provider->id,
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string'
        ]);

        // Set is_active based on checkbox value
        $validated['is_active'] = $request->has('is_active');

        User::where('email', $provider->email)
        ->where('provider_id', $provider->id)
        ->update([
            'name' => $request->pic_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number
        ]);
        
        $provider->update($validated);

        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider berhasil diperbarui');
    }

    public function destroy(Provider $provider)
    {
        $provider->delete();
        
        return redirect()
            ->route('admin.providers.index')
            ->with('success', 'Provider berhasil dihapus');
    }
}
