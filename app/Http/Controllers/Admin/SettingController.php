<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read-setting'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:create-setting'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:update-setting'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delete-setting'], ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Setting::query();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($setting) {
                    $edit = '';
                    $delete = '';
                    
                    if (auth()->user()->can('update-setting')) {
                        $edit = '<a href="' . route('admin.settings.edit', $setting->getAttributes()['key']) . '" class="text-body">';
                        $edit .= '<i class="ti ti-edit ti-sm me-2"></i>';
                        $edit .= '</a>';
                    }
                    
                    if (auth()->user()->can('delete-setting')) {
                        $delete = '<a href="#" class="text-body delete-record btn-delete" ';
                        $delete .= 'data-bs-toggle="modal" ';
                        $delete .= 'data-bs-target="#deleteModal" ';
                        $delete .= 'data-url="' . route('admin.settings.destroy', $setting->getAttributes()['key']) . '" ';
                        $delete .= 'data-name="' . $setting->getAttributes()['key'] . '">';
                        $delete .= '<i class="ti ti-trash ti-sm mx-2"></i>';
                        $delete .= '</a>';
                    }
                    
                    return '<div class="d-flex align-items-center">' . $edit . $delete . '</div>';
                })
                ->editColumn('key', function($setting) {
                    return $setting->getAttributes()['key'];
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $title = 'Pengaturan';
        return view('admin.settings.index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Pengaturan Baru';
        return view('admin.settings.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:app_config,key',
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Setting::create($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully.');
    }

    public function edit(Setting $setting)
    {
        $title = 'Ubah Pengaturan';
        return view('admin.settings.edit', compact('setting', 'title'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            // 'key' => 'required|string|max:255|unique:app_config,key,' . $setting->getAttributes()['key'],
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $setting->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();
        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully.');
    }
}
