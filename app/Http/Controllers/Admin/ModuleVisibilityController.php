<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\CmsModuleRegistry;
use App\Support\CmsModuleVisibility;
use App\Support\FrontendViewCache;
use Illuminate\Http\Request;

class ModuleVisibilityController extends Controller
{
    public function update(Request $request, string $module)
    {
        if (! CmsModuleRegistry::has($module)) {
            abort(404);
        }

        $enabled = $request->boolean('enabled');
        CmsModuleVisibility::setEnabled($module, $enabled);
        FrontendViewCache::forgetSettingsPluck();

        $def = CmsModuleRegistry::definitions()[$module];
        $message = $enabled
            ? $def['label'].' is now visible on the website. Refresh site caches, then check the live site.'
            : $def['label'].' is hidden on the website. Existing content stays in the admin.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'enabled' => $enabled,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
