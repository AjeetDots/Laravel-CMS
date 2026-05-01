<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller {
    public function index() {
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }
    public function update(UpdateSettingRequest $request) {
        $data = $request->validated();

        // Handle logo uploads separately
        unset($data['site_logo'], $data['site_logo_footer']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        foreach (['site_logo', 'site_logo_footer'] as $field) {
            if ($request->hasFile($field)) {
                $old = Setting::get($field);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('settings', 'public');
                Setting::set($field, $path);
            }
            if ($request->boolean('remove_' . $field)) {
                $old = Setting::get($field);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set($field, null);
            }
        }

        return back()->with('success', 'Settings updated.');
    }
}
