<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::get();
        $title = 'Pengaturan';
        return view('admin.settings.index', compact('settings', 'title'));
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
